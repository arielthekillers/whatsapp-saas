<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\SessionRepository;
use App\Repositories\MessageRepository;
use App\Repositories\WebhookRepository;
use App\Repositories\JobRepository;

class WahaWebhookService
{
    private SessionRepository $sessions;
    private MessageRepository $messages;
    private WebhookRepository $webhooks;
    private JobRepository $jobs;

    public function __construct()
    {
        $this->sessions = new SessionRepository();
        $this->messages = new MessageRepository();
        $this->webhooks = new WebhookRepository();
        $this->jobs = new JobRepository();
    }

    /**
     * Memproses inbound event dari WAHA.
     */
    public function processEvent(int $inboundEventId, string $eventType, string $sessionName, array $payload): void
    {
        // 1. Temukan session lokal berdasarkan waha_session_name
        $session = $this->sessions->findByWahaSessionName($sessionName);
        if (!$session) {
            // Jika session tidak ditemukan, abaikan event
            return;
        }

        $sessionId = (int) $session['id'];
        $userId = (int) $session['user_id'];
        $eventData = $payload['payload'] ?? [];

        switch ($eventType) {
            case 'session.status':
                $this->handleSessionStatus($sessionId, $userId, $eventData);
                break;

            case 'message':
                $this->handleMessage($sessionId, $userId, $eventData);
                break;

            case 'message.ack':
                $this->handleMessageAck($sessionId, $userId, $eventData);
                break;
        }
    }

    private function handleSessionStatus(int $sessionId, int $userId, array $eventData): void
    {
        $status = (string) ($eventData['status'] ?? '');
        if ($status === '') {
            return;
        }

        // Mapping status WAHA ke database lokal jika berbeda
        // WAHA statuses: STOPPED, STARTING, SCAN_QR_CODE, WORKING, FAILED, etc.
        $mappedStatus = $status;
        if ($status === 'SCAN_QR_CODE') {
            $mappedStatus = 'SCAN_QR';
        }

        $qr = $eventData['qr'] ?? null;
        $this->sessions->updateStatus($sessionId, $mappedStatus, $qr);

        // Jika status WORKING, coba update nomor telepon session dari data WAHA jika ada
        if ($status === 'WORKING' && !empty($eventData['me']['id'])) {
            // me.id biasanya berupa format "6281359774765@c.us"
            $rawPhone = $eventData['me']['id'];
            $phone = explode('@', $rawPhone)[0];
            $this->sessions->updatePhoneNumber($sessionId, $phone);
        }

        // Beritahu customer via webhook
        $this->queueCustomerWebhook($userId, $sessionId, 'session.status', [
            'session_id' => $sessionId,
            'status' => $mappedStatus,
            'timestamp' => time()
        ]);
    }

    private function handleMessage(int $sessionId, int $userId, array $eventData): void
    {
        $wahaMsgId = (string) ($eventData['id'] ?? '');
        $fromMe = (bool) ($eventData['fromMe'] ?? false);
        $body = (string) ($eventData['body'] ?? '');
        $from = (string) ($eventData['from'] ?? '');
        $to = (string) ($eventData['to'] ?? '');

        // Menentukan tipe pesan
        $msgType = (string) ($eventData['type'] ?? 'chat');
        if ($msgType === 'chat') {
            $msgType = 'text';
        }

        if ($fromMe) {
            // Outbound message: update status jika pesan sudah tercatat di database kita
            $existing = $this->messages->findByWahaMessageId($wahaMsgId);
            if ($existing) {
                $this->messages->updateStatus((int) $existing['id'], 'sent');
                $this->queueCustomerWebhook($userId, $sessionId, 'message.status', [
                    'message_id' => (int) $existing['id'],
                    'waha_message_id' => $wahaMsgId,
                    'status' => 'sent',
                    'timestamp' => time()
                ]);
            }
        } else {
            // Inbound message: simpan pesan baru ke database
            $phone = explode('@', $from)[0];
            $messageId = $this->messages->create(
                $userId,
                $sessionId,
                'inbound',
                $msgType,
                $phone,
                null, // No idempotency key for inbound
                $eventData,
                'delivered',
                $wahaMsgId
            );

            // Beritahu customer via webhook
            $this->queueCustomerWebhook($userId, $sessionId, 'message.received', [
                'message_id' => $messageId,
                'waha_message_id' => $wahaMsgId,
                'from' => $phone,
                'type' => $msgType,
                'body' => $body,
                'timestamp' => $eventData['timestamp'] ?? time()
            ]);
        }
    }

    private function handleMessageAck(int $sessionId, int $userId, array $eventData): void
    {
        $wahaMsgId = (string) ($eventData['id'] ?? '');
        $ackValue = $eventData['ack'] ?? null;

        // Cari pesan berdasarkan waha_message_id
        $message = $this->messages->findByWahaMessageId($wahaMsgId);
        if (!$message) {
            return;
        }

        $messageId = (int) $message['id'];

        // Map ack level WAHA ke status database lokal
        // 1 = Sent, 2 = Delivered, 3 = Read
        $mappedStatus = 'sent';
        if ($ackValue === 2 || $ackValue === '2') {
            $mappedStatus = 'delivered';
        } elseif ($ackValue === 3 || $ackValue === '3') {
            $mappedStatus = 'read';
        }

        $this->messages->updateStatus($messageId, $mappedStatus);
        $this->messages->logEvent($messageId, 'ack', $eventData);

        // Beritahu customer via webhook
        $this->queueCustomerWebhook($userId, $sessionId, 'message.status', [
            'message_id' => $messageId,
            'waha_message_id' => $wahaMsgId,
            'status' => $mappedStatus,
            'timestamp' => time()
        ]);
    }

    /**
     * Memasukkan log webhook customer dan memicu antrean background job.
     */
    private function queueCustomerWebhook(int $userId, int $sessionId, string $event, array $data): void
    {
        // Cari semua endpoint webhook customer yang terdaftar dan aktif untuk user + session ini
        $activeWebhooks = $this->webhooks->getActiveWebhooksForSession($userId, $sessionId);

        foreach ($activeWebhooks as $w) {
            $webhookId = (int) $w['id'];
            
            // Simpan log webhook
            $logId = $this->webhooks->createWebhookLog($webhookId, $event, $data);

            // Masukkan ke antrean job background
            $this->jobs->push('webhook_delivery', [
                'webhook_log_id' => $logId
            ]);
        }
    }
}
