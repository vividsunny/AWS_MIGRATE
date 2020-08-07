<?php

use MailPoetVendor\Twig\Environment;
use MailPoetVendor\Twig\Error\LoaderError;
use MailPoetVendor\Twig\Error\RuntimeError;
use MailPoetVendor\Twig\Markup;
use MailPoetVendor\Twig\Sandbox\SecurityError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedTagError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedFilterError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedFunctionError;
use MailPoetVendor\Twig\Source;
use MailPoetVendor\Twig\Template;

/* automatic_emails.html */
class __TwigTemplate_67f0c95a3d65ef3d6d0128ae4dbec9d59872f5427f22330fbe5566e21bf17e14 extends \MailPoetVendor\Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'translations' => [$this, 'block_translations'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<script type=\"text/javascript\">
  var mailpoet_woocommerce_automatic_emails = ";
        // line 2
        echo json_encode(($context["automatic_emails"] ?? null));
        echo ";
  var mailpoet_woocommerce_optin_on_checkout = \"";
        // line 3
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["woocommerce_optin_on_checkout"] ?? null), "html", null, true);
        echo "\";
</script>

";
        // line 6
        $this->displayBlock('translations', $context, $blocks);
    }

    public function block_translations($context, array $blocks = [])
    {
        // line 7
        echo "  ";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(["automaticEmail" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Automatic Email"), "tip" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Tip:"), "selectAutomaticEmailsEventsConditionsHeading" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select %1s events conditions"), "sendAutomaticEmailWhenHeading" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Send this %1s Automatic Email when..."), "automaticEmailActivated" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Your %1s Automatic Email is now activated!"), "automaticEmailActivationFailed" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Your %1s Automatic Email could not be activated, please check the settings."), "automaticEmailEventOptionsNotConfigured" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("You need to configure email options before this email can be sent."), "sentToXCustomers" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Sent to %\$1d customers"), "wooCommerceEmailsWarning" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("WooCommerce emails won’t be sent to new customers because the opt-in on checkout is disabled. Enable it so they can immediately get your emails after their first purchase."), "wooCommerceEmailsWarningLink" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Edit WooCommerce settings")]);
        // line 18
        echo "
";
    }

    public function getTemplateName()
    {
        return "automatic_emails.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 18,  50 => 7,  44 => 6,  38 => 3,  34 => 2,  31 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "automatic_emails.html", "/nas/content/live/popupcomicshop/wp-content/plugins/mailpoet/views/automatic_emails.html");
    }
}
