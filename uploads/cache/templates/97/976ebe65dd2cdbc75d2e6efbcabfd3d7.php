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

/* formats/familyAddresses.twig.html */
class __TwigTemplate_81a8e58cf78cdcb15ec3f13ab993cedc extends Template
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
        $context['_seq'] = CoreExtension::ensureTraversable(($context["families"] ?? null));
        $context['_iterated'] = false;
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
        foreach ($context['_seq'] as $context["_key"] => $context["family"]) {
            // line 15
            yield "
    ";
            // line 16
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "length", [], "any", false, false, false, 16) > 1)) {
                // line 17
                yield "        <u>";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["family"], "name", [], "any", false, false, false, 17), "html", null, true);
                yield "</u><br/>
    ";
            }
            // line 19
            yield "
    ";
            // line 20
            if (($context["includeAddressName"] ?? null)) {
                // line 21
                yield "        ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["family"], "nameAddress", [], "any", false, false, false, 21), "html", null, true);
                yield "<br/>
    ";
            }
            // line 23
            yield "
    ";
            // line 24
            yield $this->env->getFunction('formatUsing')->getCallable()("address", CoreExtension::getAttribute($this->env, $this->source, $context["family"], "homeAddress", [], "any", false, false, false, 24), CoreExtension::getAttribute($this->env, $this->source, $context["family"], "homeAddressDistrict", [], "any", false, false, false, 24), CoreExtension::getAttribute($this->env, $this->source, $context["family"], "homeAddressCountry", [], "any", false, false, false, 24));
            yield "

    ";
            // line 26
            if ( !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 26)) {
                yield "<br/><br/>";
            }
            // line 27
            yield "
";
            $context['_iterated'] = true;
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        if (!$context['_iterated']) {
            // line 29
            yield "
    ";
            // line 30
            yield $this->env->getFunction('formatUsing')->getCallable()("address", CoreExtension::getAttribute($this->env, $this->source, ($context["person"] ?? null), "address1", [], "any", false, false, false, 30), CoreExtension::getAttribute($this->env, $this->source, ($context["person"] ?? null), "address1District", [], "any", false, false, false, 30), CoreExtension::getAttribute($this->env, $this->source, ($context["person"] ?? null), "address1Country", [], "any", false, false, false, 30));
            yield "

";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['family'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "formats/familyAddresses.twig.html";
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
        return array (  110 => 30,  107 => 29,  93 => 27,  89 => 26,  84 => 24,  81 => 23,  75 => 21,  73 => 20,  70 => 19,  64 => 17,  62 => 16,  59 => 15,  41 => 14,  38 => 13,);
    }

    public function getSourceContext()
    {
        return new Source("", "formats/familyAddresses.twig.html", "/var/www/html/gibbon/resources/templates/formats/familyAddresses.twig.html");
    }
}
