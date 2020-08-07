<?php

namespace MailPoet\Premium\Config;

if (!defined('ABSPATH')) exit;


use MailPoet\Cron\Workers\StatsNotifications\Worker;
use MailPoet\DI\ContainerWrapper;
use MailPoet\Premium\Config\Hooks as ConfigHooks;
use MailPoet\WP\Functions as WPFunctions;

class Initializer {
  private $renderer;

  /** @var WPFunctions */
  private $wp;

  /** @var ConfigHooks */
  private $hooks;

  const INITIALIZED = 'MAILPOET_PREMIUM_INITIALIZED';

  public function __construct(WPFunctions $wp, ConfigHooks $hooks) {
    $this->wp = $wp;
    $this->hooks = $hooks;
  }

  public function init($params = [
    'file' => '',
    'version' => '1.0.0',
  ]) {
    Env::init($params['file'], $params['version']);

    $this->wp->addAction('mailpoet_initialized', [
      $this,
      'setup',
    ]);
  }

  public function setup() {
    $this->setupLocalizer();
    $this->setupRenderer();

    $this->wp->addAction(
      'mailpoet_styles_admin_after',
      [$this, 'includePremiumStyles']
    );

    $this->wp->addAction(
      'mailpoet_scripts_admin_before',
      [$this, 'includePremiumJavascript']
    );

    $this->setupCampaignStats();

     $this->hooks->init();

    if (!defined(self::INITIALIZED)) {
      define(self::INITIALIZED, true);
    }
  }

  public function setupRenderer() {
    $container = ContainerWrapper::getInstance(WP_DEBUG)->getPremiumContainer();
    $this->renderer = $container->get(Renderer::class);
  }

  public function setupLocalizer() {
    $localizer = new Localizer();
    $localizer->init();
  }

  public function setupCampaignStats() {
    $this->wp->addAction(
      'mailpoet_newsletters_translations_after',
      [$this, 'newslettersCampaignStats']
    );
  }

  public function newslettersCampaignStats() {
    // shortcode URLs to substitute with user-friendly names
    $data['shortcode_links'] = Worker::getShortcodeLinksMapping();

    echo $this->renderer->render('newsletters/campaign_stats.html', $data);
  }

  public function includePremiumStyles() {
    echo $this->renderer->render('styles.html');
  }

  public function includePremiumJavascript() {
    echo $this->renderer->render('scripts.html');
  }
}
