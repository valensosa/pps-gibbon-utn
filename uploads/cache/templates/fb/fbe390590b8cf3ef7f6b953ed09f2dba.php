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

/* libraryShelves.twig.html */
class __TwigTemplate_695b1793ec5bc09e0906f679d4c38466 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        yield "
";
        // line 2
        $macros["shelfItemViewer"] = $this->macros["shelfItemViewer"] = $this;
        // line 3
        if ((($context["libraryShelves"] ?? null) && ($context["shelfNames"] ?? null))) {
            // line 4
            yield "    <div id=\"libraryShelves\" class=\"shelfContainer overflow-y-auto w-full\">
        <!-- <h1>Hand-Picked Collections</h1> -->
        <div class=\"columns-auto w-full divide-y\">
        ";
            // line 7
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(Twig\Extension\CoreExtension::keys(($context["libraryShelves"] ?? null)));
            foreach ($context['_seq'] as $context["_key"] => $context["shelf"]) {
                // line 8
                yield "            <h3 class=\"mt-8\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_0 = ($context["shelfNames"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[$context["shelf"]] ?? null) : null), "html", null, true);
                yield "</h3>
            <div class=\"flex justify-content-start pl-4 pr-4 pt-4 content-end gap-5 flex-row  overflow-x-scroll overscroll-contain pb-8 border-2 border-transparent bg-gray-100 bg-opacity-50\">
                ";
                // line 10
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable((($__internal_compile_1 = ($context["libraryShelves"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1[$context["shelf"]] ?? null) : null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 11
                    yield "                    ";
                    if ( !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "imageLocation", [], "any", false, false, false, 11))) {
                        // line 12
                        yield "                        <a data-log=\"";
                        yield CoreExtension::callMacro($macros["shelfItemViewer"], "macro_tooltip", [$context["item"]], 12, $context, $this->getSourceContext());
                        yield "\">
                            <img src=\"";
                        // line 13
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::inFilter("?", CoreExtension::getAttribute($this->env, $this->source, $context["item"], "imageLocation", [], "any", false, false, false, 13))) ? ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "imageLocation", [], "any", false, false, false, 13) . "&fife=w200")) : (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "imageLocation", [], "any", false, false, false, 13))), "html", null, true);
                        yield "\" class=\"transition ease-out duration-300 border-2 border-transparent hover hover:border-purple-600 transform hover:-translate-y-1 hover:scale-105 hover:shadow-2xl w-40 h-64 shadow-xl\">
                        </a>
                    ";
                    }
                    // line 16
                    yield "                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 17
                yield "            </div>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['shelf'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 19
            yield "        </div>
    </div>

    

    <script>
    \$('#libraryShelves').tooltip({
        items: \"a[data-log]\",
        show: 800,
        hide: false,
        content: function () {
            return \$(this).data('log');
        },
        tooltipClass: \"tooltip-reset\",
        position: {
            my: \"center bottom-5\",
            at: \"center top\",
            using: function (position, feedback) {
                \$(this).css(position);
                \$(\"<div>\").
                    addClass(\"arrow\").
                    addClass(feedback.vertical).
                    addClass(feedback.horizontal).
                    appendTo(this);
            }
        }
    });
    </script>
    <style>
        .tooltip-reset {
            min-width: 20rem;
        }
    </style>
";
        }
        // line 53
        yield "
";
        return; yield '';
    }

    // line 54
    public function macro_tooltip($__item__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "item" => $__item__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 55
            yield "    <section class='flex flex-row p-1 w-auto'>
        <div class='flex-1'>
            <h4 class='mt-2 text-white'>";
            // line 57
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "name", [], "any", false, false, false, 57), "html", null, true);
            yield "</h4>
            <p class='text-white'>by: ";
            // line 58
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "producer", [], "any", false, false, false, 58), "html", null, true);
            yield "</p>
            <p class='text-white'>Location: ";
            // line 59
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "spaceName", [], "any", false, false, false, 59), "html", null, true);
            yield "</p>
            <p class='text-white'>Location Detail: ";
            // line 60
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "locationDetail", [], "any", false, false, false, 60), "html", null, true);
            yield "</p>
        
            
            ";
            // line 63
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "status", [], "any", false, false, false, 63) == "Available")) {
                // line 64
                yield "                <div><p class='text-green-500'>Status: ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "status", [], "any", false, false, false, 64), "html", null, true);
                yield "</p></div>
            ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 65
($context["item"] ?? null), "status", [], "any", false, false, false, 65) == "On Loan")) {
                // line 66
                yield "                <div><p class='text-red-500'>Status: ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "status", [], "any", false, false, false, 66), "html", null, true);
                yield "</p></div>
            ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 67
($context["item"] ?? null), "status", [], "any", false, false, false, 67) == "Reserved")) {
                // line 68
                yield "                <div><p class='text-yellow-500'>Status: ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "status", [], "any", false, false, false, 68), "html", null, true);
                yield "</p></div>
            ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 69
($context["item"] ?? null), "status", [], "any", false, false, false, 69) == "Repair")) {
                // line 70
                yield "                <div><p class='text-Orange-500'>Status: ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "status", [], "any", false, false, false, 70), "html", null, true);
                yield "</p></div>
            ";
            }
            // line 72
            yield "        </div>
        <div class='toolTipDescription flex-1 pl-2'>
            <h5 class='mt-2 text-white'>Description:</h5>
            <p class='text-white'>";
            // line 75
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::slice($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "description", [], "any", false, false, false, 75), 0, 200), "html", null, true);
            yield "...</p>
        </div>
    </section>
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "libraryShelves.twig.html";
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
        return array (  201 => 75,  196 => 72,  190 => 70,  188 => 69,  183 => 68,  181 => 67,  176 => 66,  174 => 65,  169 => 64,  167 => 63,  161 => 60,  157 => 59,  153 => 58,  149 => 57,  145 => 55,  133 => 54,  127 => 53,  91 => 19,  84 => 17,  78 => 16,  72 => 13,  67 => 12,  64 => 11,  60 => 10,  54 => 8,  50 => 7,  45 => 4,  43 => 3,  41 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "libraryShelves.twig.html", "/var/www/html/gibbon/modules/Library/templates/libraryShelves.twig.html");
    }
}
