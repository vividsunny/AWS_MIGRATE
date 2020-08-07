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

/* woocommerce_list_import.html */
class __TwigTemplate_95afd4ca4199787dc407c5342f0f7dc5bb8a7e05555eed4349d7e610e9ced8ad extends \MailPoetVendor\Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'translations' => [$this, 'block_translations'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("layout.html", "woocommerce_list_import.html", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        // line 4
        echo "<script>
  var mailpoet_logo_url = '";
        // line 5
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateCdnUrl("welcome-wizard/mailpoet-logo.20190109-1400.png");
        echo "';
  var finish_wizard_url = '";
        // line 6
        echo \MailPoetVendor\twig_escape_filter($this->env, ($context["finish_wizard_url"] ?? null), "html", null, true);
        echo "';
</script>

<div id=\"mailpoet_wizard_container\"></div>

";
    }

    // line 13
    public function block_translations($context, array $blocks = [])
    {
        // line 14
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(["wooCommerceListImportTitle" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("WooCommerce customers now have their own list", "Title on the customers import page"), "wooCommerceListImportInfo1" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("MailPoet will create a list of your WooCommerce customers, even those who don’t have an account, known as \"Guests\"."), "wooCommerceListImportInfo2" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("New customers will be able to join this list during checkout. You can manage this new checkout feature in your MailPoet Settings."), "wooCommerceListImportInfo3" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("To begin, please choose how you want to populate your list:"), "wooCommerceListImportCheckboxSubscribed" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("<b>add and subscribe</b> all my customers to this list because they agreed to receive marketing emails from me"), "wooCommerceListImportCheckboxUnsubscribed" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("<b>add</b> all my customers to the list, but <b>as unsubscribed</b>. They can join this list next time they check out"), "wooCommerceListImportInfo4" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Their subscription preference on other lists won’t be changed."), "wooCommerceListImportSubmit" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Create my WooCommerce Customers list now!", "Submit button caption"), "unknownError" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Unknown error")]);
        // line 24
        echo "
";
    }

    public function getTemplateName()
    {
        return "woocommerce_list_import.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  65 => 24,  63 => 14,  60 => 13,  50 => 6,  46 => 5,  43 => 4,  40 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "woocommerce_list_import.html", "/nas/content/live/popupcomicshop/wp-content/plugins/mailpoet/views/woocommerce_list_import.html");
    }
}
