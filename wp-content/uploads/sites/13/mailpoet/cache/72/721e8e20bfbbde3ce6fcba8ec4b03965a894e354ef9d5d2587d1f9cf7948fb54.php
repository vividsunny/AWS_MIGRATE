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

/* newsletter/templates/blocks/container/emptyBlock.hbs */
class __TwigTemplate_8711f76d9e037ae10f7fbd073db35de1b0b234dcc2c88d76ff22f66de607e6f0 extends \MailPoetVendor\Twig\Template
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
        echo "<div class=\"mailpoet_container_empty\">{{#ifCond emptyContainerMessage '!==' ''}}{{emptyContainerMessage}}{{else}}{{#if isRoot}}";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Add a column block here.");
        echo "{{else}}";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Add a content block here.");
        echo "{{/if}}{{/ifCond}}</div>
{{debug}}
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/container/emptyBlock.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "newsletter/templates/blocks/container/emptyBlock.hbs", "/nas/content/live/popupcomicshop/wp-content/plugins/mailpoet/views/newsletter/templates/blocks/container/emptyBlock.hbs");
    }
}
