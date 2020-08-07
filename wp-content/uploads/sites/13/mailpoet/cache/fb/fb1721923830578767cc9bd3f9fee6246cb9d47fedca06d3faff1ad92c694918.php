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

/* emails/newSubscriberNotification.html */
class __TwigTemplate_127dc183fc5998169a167185ad6b6615a326c44f998dd5d85c111171c2d93e62 extends \MailPoetVendor\Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<p>";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Howdy,");
        echo "

<p>";
        // line 3
        echo \MailPoetVendor\twig_escape_filter($this->env, \MailPoetVendor\twig_replace_filter($this->env->getExtension('MailPoet\Twig\I18n')->translate("The subscriber %\$1s has just subscribed to your list %\$2s!"), ["%\$1s" =>         // line 4
($context["subscriber_email"] ?? null), "%\$2s" => ($context["segments_names"] ?? null)]), "html", null, true);
        // line 5
        echo "

<p>";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Cheers,");
        echo "

<p>";
        // line 9
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("The MailPoet Plugin");
        echo "

<p><small>";
        // line 11
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate(MailPoet\Util\Helpers::replaceLinkTags("You can disable these emails in your [link]MailPoet Settings.[/link]",         // line 12
($context["link_settings"] ?? null)));
        // line 13
        echo "</small>

";
        // line 15
        if ((\MailPoetVendor\twig_date_format_filter($this->env, "now", "Y-m-d") < \MailPoetVendor\twig_date_format_filter($this->env, "2018-11-30", "Y-m-d"))) {
            // line 16
            echo "  <p>
    <small>
      ";
            // line 18
            echo $this->env->getExtension('MailPoet\Twig\I18n')->translate(MailPoet\Util\Helpers::replaceLinkTags("PS. MailPoet annual plans are nearly half price for a limited time.
      [link]Find out more in the Premium page in your admin.[/link]",             // line 20
($context["link_premium"] ?? null)));
            // line 21
            echo "
  </small>
";
        }
    }

    public function getTemplateName()
    {
        return "emails/newSubscriberNotification.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 21,  68 => 20,  66 => 18,  62 => 16,  60 => 15,  56 => 13,  54 => 12,  53 => 11,  48 => 9,  43 => 7,  39 => 5,  37 => 4,  36 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "emails/newSubscriberNotification.html", "/nas/content/live/popupcomicshop/wp-content/plugins/mailpoet/views/emails/newSubscriberNotification.html");
    }
}
