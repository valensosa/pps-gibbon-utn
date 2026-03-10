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

/* formats/familyContacts.twig.html */
class __TwigTemplate_478b3b5937214021e9e59e88992249bc extends Template
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
        // line 13
        yield "
";
        // line 14
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["familyAdults"] ?? null));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["adult"]) {
            // line 15
            yield "
    <u>";
            // line 16
            yield $this->env->getFunction('formatUsing')->getCallable()("name", CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "title", [], "any", false, false, false, 16), CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "preferredName", [], "any", false, false, false, 16), CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "surname", [], "any", false, false, false, 16), "Parent");
            yield "</u>
    ";
            // line 17
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "status", [], "any", false, false, false, 17) != "Full")) {
                yield "<i>(";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "status", [], "any", false, false, false, 17)), "html", null, true);
                yield ")</i>";
            }
            // line 18
            yield "    <br/>

    ";
            // line 20
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "childDataAccess", [], "any", false, false, false, 20) == "N")) {
                // line 21
                yield "        <strong style=\"color: #cc0000\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Data Access"), "html", null, true);
                yield ": ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("No"), "html", null, true);
                yield "</strong><br/>
    ";
            }
            // line 23
            yield "
    ";
            // line 24
            if (CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "email", [], "any", false, false, false, 24)) {
                // line 25
                yield "        <i>";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Email"), "html", null, true);
                yield "</i>: ";
                yield $this->env->getFunction('formatUsing')->getCallable()("link", ("mailto:" . CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "email", [], "any", false, false, false, 25)), CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "email", [], "any", false, false, false, 25));
                yield "<br/>
    ";
            }
            // line 27
            yield "
    ";
            // line 28
            if (($context["includePhoneNumbers"] ?? null)) {
                // line 29
                yield "        ";
                $context["phoneNumbers"] = 0;
                // line 30
                yield "
        ";
                // line 31
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(range(1, 4));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 32
                    yield "            ";
                    if ((($__internal_compile_0 = $context["adult"]) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[("phone" . $context["i"])] ?? null) : null)) {
                        // line 33
                        yield "                ";
                        yield $this->env->getFunction('formatUsing')->getCallable()("phone", (($__internal_compile_1 = $context["adult"]) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1[("phone" . $context["i"])] ?? null) : null), (($__internal_compile_2 = $context["adult"]) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2[(("phone" . $context["i"]) . "CountryCode")] ?? null) : null), (("<i>" . (($__internal_compile_3 = $context["adult"]) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3[(("phone" . $context["i"]) . "Type")] ?? null) : null)) . "</i>"));
                        yield "<br/>
                ";
                        // line 34
                        $context["phoneNumbers"] = (($context["phoneNumbers"] ?? null) + 1);
                        // line 35
                        yield "            ";
                    }
                    // line 36
                    yield "        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 37
                yield "
        ";
                // line 38
                if ((($context["phoneNumbers"] ?? null) == 0)) {
                    // line 39
                    yield "            <i>";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Phone"), "html", null, true);
                    yield ": ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("No number available."), "html", null, true);
                    yield "</i><br/>
        ";
                }
                // line 41
                yield "
    ";
            }
            // line 43
            yield "
    ";
            // line 44
            if (($context["includeCitizenship"] ?? null)) {
                // line 45
                yield "
        ";
                // line 46
                if (CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "citizenship", [], "any", false, false, false, 46)) {
                    // line 47
                    yield "        <i>";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Nationality"), "html", null, true);
                    yield "</i>: ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["adult"], "citizenship", [], "any", false, false, false, 47), "html", null, true);
                    yield "
        ";
                }
                // line 49
                yield "
    ";
            }
            // line 51
            yield "
    ";
            // line 52
            if ( !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 52)) {
                yield "<br/><br/>";
            }
            // line 53
            yield "
";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['adult'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "formats/familyContacts.twig.html";
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
        return array (  178 => 53,  174 => 52,  171 => 51,  167 => 49,  159 => 47,  157 => 46,  154 => 45,  152 => 44,  149 => 43,  145 => 41,  137 => 39,  135 => 38,  132 => 37,  126 => 36,  123 => 35,  121 => 34,  116 => 33,  113 => 32,  109 => 31,  106 => 30,  103 => 29,  101 => 28,  98 => 27,  90 => 25,  88 => 24,  85 => 23,  77 => 21,  75 => 20,  71 => 18,  65 => 17,  61 => 16,  58 => 15,  41 => 14,  38 => 13,);
    }

    public function getSourceContext()
    {
        return new Source("", "formats/familyContacts.twig.html", "/var/www/html/gibbon/resources/templates/formats/familyContacts.twig.html");
    }
}
