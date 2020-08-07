<?php

namespace MailPoet\Premium\API\JSON\v1\ResponseBuilders;

if (!defined('ABSPATH')) exit;


use MailPoet\Entities\NewsletterEntity;
use MailPoet\Entities\NewsletterSegmentEntity;
use MailPoet\Entities\SegmentEntity;
use MailPoet\Newsletter\Statistics\NewsletterStatistics;
use MailPoetVendor\Doctrine\Common\Collections\ArrayCollection;

class StatsResponseBuilder {
  const DATE_FORMAT = 'Y-m-d H:i:s';

  public function build(
    NewsletterEntity $newsletter,
    NewsletterStatistics $statistics,
    array $clickedLinks,
    string $previewUrl
  ): array {
    $queue = $newsletter->getLatestQueue();
    $task = $queue->getTask();
    $segments = $newsletter->getNewsletterSegments();
    return [
      'id' => (string)$newsletter->getId(),
      'subject' => $newsletter->getSubject(),
      'sender_address' => $newsletter->getSenderAddress(),
      'sender_name' => $newsletter->getSenderName(),
      'reply_to_address' => $newsletter->getReplyToAddress(),
      'reply_to_name' => $newsletter->getReplyToName(),
      'segments' => $this->buildSegments($segments),
      'hash' => $newsletter->getHash(),
      'queue' => [
        'id' => $queue->getId(),
        'scheduled_at' => is_null($task->getScheduledAt()) ? null : $task->getScheduledAt()->format(self::DATE_FORMAT),
        'created_at' => $task->getCreatedAt()->format(self::DATE_FORMAT),
      ],
      'statistics' => $statistics->asArray(),
      'total_sent' => $statistics->getTotalSentCount(),
      'ga_campaign' => $newsletter->getGaCampaign(),
      'clicked_links' => $clickedLinks,
      'preview_url' => $previewUrl,
    ];
  }

  /**
   * @param NewsletterSegmentEntity[]|ArrayCollection $segments
   * @return array
   */
  private function buildSegments($segments): array {
    $result = [];
    foreach ($segments as $newsletterSegment) {
      $segment = $newsletterSegment->getSegment();
      if ($segment instanceof SegmentEntity) {
        $result[] = [
          'name' => $segment->getName(),
        ];
      }
    }
    return $result;
  }
}
