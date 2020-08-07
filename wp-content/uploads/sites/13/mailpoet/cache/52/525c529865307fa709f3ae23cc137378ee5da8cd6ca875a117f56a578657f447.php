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

/* newsletter/templates/blocks/social/settingsStyles.hbs */
class __TwigTemplate_3b7bf15998facab821e352a4581e17b37ee141051a83d8d30deb2fb122267ff8 extends \MailPoetVendor\Twig\Template
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
        echo "{{#each availableSets}}
    <div class=\"mailpoet_social_icon_set{{#ifCond ../activeSet '==' this }} mailpoet_active_icon_set{{/ifCond}}\" data-setName=\"{{ this }}\">
    {{#each ../availableSocialIcons}}<img src=\"{{lookup (lookup ../../socialIconSets ../this) this}}\" />{{/each}}
    </div>
{{/each}}
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/social/settingsStyles.hbs";
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
        return new Source("", "newsletter/templates/blocks/social/settingsStyles.hbs", "/nas/content/live/popupcomicshop/wp-content/plugins/mailpoet/views/newsletter/templates/blocks/social/settingsStyles.hbs");
    }
}
