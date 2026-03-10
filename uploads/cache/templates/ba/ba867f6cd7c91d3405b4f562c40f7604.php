<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* components/formBuilder.twig.html */
class __TwigTemplate_1dc4fb9f5723fca6363a998e2d0ea44c extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'blankslate' => [$this, 'block_blankslate'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 10
        yield "
<section class=\"lg:flex items-stretch\">
    <div class=\"flex-1 pr-6\">

        ";
        // line 14
        yield CoreExtension::getAttribute($this->env, $this->source, ($context["fields"] ?? null), "getOutput", [], "any", false, false, false, 14);
        yield "

        ";
        // line 16
        if ((($context["fieldCount"] ?? null) <= 0)) {
            // line 17
            yield "        <div class=\"h-48 rounded-sm border bg-gray-100 shadow-inner overflow-hidden\">
            ";
            // line 18
            yield from $this->unwrap()->yieldBlock('blankslate', $context, $blocks);
            // line 21
            yield "        </div>
        ";
        }
        // line 23
        yield "    </div>

    <div id=\"sections\" class=\"w-full lg:w-sidebar lg:max-w-xs h-full\">
        <h3>
            ";
        // line 27
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Add Fields"), "html", null, true);
        yield " <span class=\"text-xxs font-normal\"></span>
        </h3>

        <a href=\"";
        // line 30
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/fullscreen.php?q=/modules/System Admin/formBuilder_page_edit_field_add.php&fieldGroup=AllFields&gibbonFormID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormID"] ?? null), "html", null, true);
        yield "&gibbonFormPageID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormPageID"] ?? null), "html", null, true);
        yield "&width=900&height=700\" class=\"thickbox w-full p-4 mb-2 flex items-center justify-start group cursor-pointer p-2 mr-2 mb-2 bg-gray-100 border rounded hover:border-gray-500 hover:bg-gray-300\">
            <img class=\"w-6 h-6 mr-4 opacity-50 pointer-events-none\" src=\"";
        // line 31
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/modules/Reports/img/icons/align-justify.svg\">
            <div class=\"text-center text-gray-700 text-sm leading-tight pointer-events-none\">";
        // line 32
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Application Fields"), "html", null, true);
        yield "</div>
        </a>

        <a href=\"";
        // line 35
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/fullscreen.php?q=/modules/System Admin/formBuilder_page_edit_field_add.php&fieldGroup=GenericFields&gibbonFormID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormID"] ?? null), "html", null, true);
        yield "&gibbonFormPageID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormPageID"] ?? null), "html", null, true);
        yield "&width=900&height=500\" class=\"thickbox w-full p-4 mb-2 flex items-center justify-start group cursor-pointer p-2 mr-2 mb-2 bg-gray-100 border rounded hover:border-gray-500 hover:bg-gray-300\">
            <img class=\"w-6 h-6 mr-4 opacity-50 pointer-events-none\" src=\"";
        // line 36
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/modules/Reports/img/icons/grip-lines.svg\">
            <div class=\"text-center text-gray-700 text-sm leading-tight pointer-events-none\">";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Generic Fields"), "html", null, true);
        yield "</div>
        </a>

        <a href=\"";
        // line 40
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/fullscreen.php?q=/modules/System Admin/formBuilder_page_edit_field_add.php&fieldGroup=CustomFields&gibbonFormID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormID"] ?? null), "html", null, true);
        yield "&gibbonFormPageID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormPageID"] ?? null), "html", null, true);
        yield "&width=900&height=500\" class=\"thickbox w-full p-4 mb-2 flex items-center justify-start group cursor-pointer p-2 mr-2 mb-2 bg-gray-100 border rounded hover:border-gray-500 hover:bg-gray-300\">
            <img class=\"w-6 h-6 mr-4 opacity-50 pointer-events-none\" src=\"";
        // line 41
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/modules/Reports/img/icons/grip-lines.svg\">
            <div class=\"text-center text-gray-700 text-sm leading-tight pointer-events-none\">";
        // line 42
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Custom Fields"), "html", null, true);
        yield "</div>
        </a>

        <a href=\"";
        // line 45
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/fullscreen.php?q=/modules/System Admin/formBuilder_page_edit_field_add.php&fieldGroup=PersonalDocuments&gibbonFormID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormID"] ?? null), "html", null, true);
        yield "&gibbonFormPageID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormPageID"] ?? null), "html", null, true);
        yield "&width=900&height=500\" class=\"thickbox w-full p-4 mb-2 flex items-center justify-start group cursor-pointer p-2 mr-2 mb-2 bg-gray-100 border rounded hover:border-gray-500 hover:bg-gray-300\">
            <img class=\"w-6 h-6 mr-4 opacity-50 pointer-events-none\" src=\"";
        // line 46
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/modules/Reports/img/icons/address-card.svg\">
            <div class=\"text-center text-gray-700 text-sm leading-tight pointer-events-none\">";
        // line 47
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Personal Documents"), "html", null, true);
        yield "</div>
        </a>

        <a href=\"";
        // line 50
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/fullscreen.php?q=/modules/System Admin/formBuilder_page_edit_field_add.php&fieldGroup=RequiredDocuments&gibbonFormID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormID"] ?? null), "html", null, true);
        yield "&gibbonFormPageID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormPageID"] ?? null), "html", null, true);
        yield "&width=900&height=500\" class=\"thickbox w-full p-4 mb-2 flex items-center justify-start group cursor-pointer p-2 mr-2 mb-2 bg-gray-100 border rounded hover:border-gray-500 hover:bg-gray-300\">
            <img class=\"w-6 h-6 mr-4 opacity-50 pointer-events-none\" src=\"";
        // line 51
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/modules/Reports/img/icons/file-alt.svg\">
            <div class=\"text-center text-gray-700 text-sm leading-tight pointer-events-none\">";
        // line 52
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Required Documents"), "html", null, true);
        yield "</div>
        </a>

        <h3>
            ";
        // line 56
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Layout"), "html", null, true);
        yield " <span class=\"text-xxs font-normal\"></span>
        </h3>

        <a href=\"";
        // line 59
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/fullscreen.php?q=/modules/System Admin/formBuilder_page_edit_field_add.php&fieldGroup=LayoutHeadings&gibbonFormID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormID"] ?? null), "html", null, true);
        yield "&gibbonFormPageID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormPageID"] ?? null), "html", null, true);
        yield "&width=900&height=500\" class=\"thickbox w-full p-4 mb-2 flex items-center justify-start group cursor-pointer p-2 mr-2 mb-2 bg-gray-100 border rounded hover:border-gray-500 hover:bg-gray-300\">
            <img class=\"w-6 h-6 mr-4 opacity-50 pointer-events-none\" src=\"";
        // line 60
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/modules/Reports/img/icons/heading.svg\">
            <div class=\"text-center text-gray-700 text-sm leading-tight pointer-events-none\">";
        // line 61
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Heading"), "html", null, true);
        yield "</div>
        </a>

        <a href=\"";
        // line 64
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/fullscreen.php?q=/modules/System Admin/formBuilder_page_edit_field_add.php&fieldGroup=LayoutText&gibbonFormID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormID"] ?? null), "html", null, true);
        yield "&gibbonFormPageID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormPageID"] ?? null), "html", null, true);
        yield "&width=900&height=500\" class=\"thickbox w-full p-4 mb-2 flex items-center justify-start group cursor-pointer p-2 mr-2 mb-2 bg-gray-100 border rounded hover:border-gray-500 hover:bg-gray-300\">
            <img class=\"w-6 h-6 mr-4 opacity-50 pointer-events-none\" src=\"";
        // line 65
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/modules/Reports/img/icons/edit.svg\">
            <div class=\"text-center text-gray-700 text-sm leading-tight pointer-events-none\">";
        // line 66
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Text"), "html", null, true);
        yield "</div>
        </a>

        <a href=\"";
        // line 69
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/fullscreen.php?q=/modules/System Admin/formBuilder_page_add.php&redirect=design&gibbonFormID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormID"] ?? null), "html", null, true);
        yield "&gibbonFormPageID=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["gibbonFormPageID"] ?? null), "html", null, true);
        yield "&width=900&height=700\" class=\"thickbox w-full p-4 mb-2 flex items-center justify-start group cursor-pointer p-2 mr-2 mb-2 bg-gray-100 border rounded hover:border-gray-500 hover:bg-gray-300\">
            <img class=\"w-6 h-6 mr-4 opacity-50 pointer-events-none\" src=\"";
        // line 70
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
        yield "/modules/Reports/img/icons/file.svg\">
            <div class=\"text-center text-gray-700 text-sm leading-tight pointer-events-none\">";
        // line 71
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("New Page"), "html", null, true);
        yield "</div>
        </a>
        
    </div>
</section>

<style>
    #formFields .formRow[style*=\"display: none\"] {
        display: flex !important;
        background-image: linear-gradient(45deg, #fafafa 25%, #f4f4f4 25%, #f4f4f4 50%, #fafafa 50%, #fafafa 75%, #f4f4f4 75%, #f4f4f4 100%);
        background-size: 23.0px 23.0px;
    }
</style>

<script>
    \$('#formFields section').sortable({
        placeholder: \"drag-placeholder bg-gray-400 shadow-inner\",
        handle: \".drag-handle\",
        connectWith: 'section',
        start: function(event, ui) {
            // console.log('sort start');
            \$(ui.item).addClass('bg-gray-100').addClass('border');
            \$(ui.placeholder).outerHeight(\$(ui.item).outerHeight());
        },
        update: function(event, ui) {
            \$(ui.item).removeClass('bg-gray-100').removeClass('border');
            var elementOrder = new Array();
            \$('.draggableRow', '#formFields').each(function() {
                elementOrder.push(\$(this).data('drag-id'));
            });

            var container = \$('#formFields');
            \$.ajax({
                url: \$(container).data('drag-url'),
                data: {
                    data: \$(container).data('drag-data'),
                    order: JSON.stringify(elementOrder)
                },
                type: 'POST',
                success: function(data) {
                    // console.log('success '+data);
                }
            });
        },
        stop: function(event, ui) {
            \$(ui.item).removeClass('bg-gray-100').removeClass('border');
        },
    });

</script>
";
        return; yield '';
    }

    // line 18
    public function block_blankslate($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 19
        yield "            ";
        yield from         $this->loadTemplate("components/blankSlate.twig.html", "components/formBuilder.twig.html", 19)->unwrap()->yield(CoreExtension::merge($context, ["blankSlate" => $this->env->getFunction('__')->getCallable()("This page is empty. Add fields to your form using the sidebar.")]));
        // line 20
        yield "            ";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/formBuilder.twig.html";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  281 => 20,  278 => 19,  274 => 18,  218 => 71,  214 => 70,  206 => 69,  200 => 66,  196 => 65,  188 => 64,  182 => 61,  178 => 60,  170 => 59,  164 => 56,  157 => 52,  153 => 51,  145 => 50,  139 => 47,  135 => 46,  127 => 45,  121 => 42,  117 => 41,  109 => 40,  103 => 37,  99 => 36,  91 => 35,  85 => 32,  81 => 31,  73 => 30,  67 => 27,  61 => 23,  57 => 21,  55 => 18,  52 => 17,  50 => 16,  45 => 14,  39 => 10,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/formBuilder.twig.html", "/var/www/html/gibbon/resources/templates/components/formBuilder.twig.html");
    }
}
