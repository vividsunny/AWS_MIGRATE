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

/* emails/newSubscriberNotification.txt */
class __TwigTemplate_956ab87f7c20f3405363d32293a673677a7f7798de41f53f72b629e3f76700a7 extends \MailPoetVendor\Twig\Template
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
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Howdy,");
        echo "

";
        // line 3
        echo \MailPoetVendor\twig_escape_filter($this->env, \MailPoetVendor\twig_replace_filter($this->env->getExtension('MailPoet\Twig\I18n')->translate("The subscriber %\$1s has just subscribed to your list %\$2s!"), ["%\$1s" =>         // line 4
($context["subscriber_email"] ?? null), "%\$2s" => ($context["segments_names"] ?? null)]), "html", null, true);
        // line 5
        echo "

";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Cheers,");
        echo "
";
        // line 8
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("The MailPoet Plugin");
        echo "

";
        // line 10
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("You can disable these emails in your MailPoet Settings.");
        echo "
";
        // line 11
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["link_settings"] ?? null), "html", null, true);
        echo "

";
        // line 13
        if ((\MailPoetVendor\twig_date_format_filter($this->env, "now", "Y-m-d") < \MailPoetVendor\twig_date_format_filter($this->env, "2018-11-30", "Y-m-d"))) {
            // line 14
            echo "    ";
            echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("PS. MailPoet annual plans are nearly half price for a limited time. Find out more in the Premium page in your admin.");
            echo "
    ";
            // line 15
            echo \MailPoetVendor\twig_escape_filter($this->env, ($context["link_premium"] ?? null), "html", null, true);
            echo "
";
        }
        // line 17
        echo "
";
    }

    public function getTemplateName()
    {
        return "emails/newSubscriberNotification.txt";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  72 => 17,  67 => 15,  62 => 14,  60 => 13,  55 => 11,  51 => 10,  46 => 8,  42 => 7,  38 => 5,  36 => 4,  35 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "emails/newSubscriberNotification.txt", "/nas/content/live/popupcomicshop/wp-content/plugins/mailpoet/views/emails/newSubscriberNotification.txt");
    }
}
