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

/* components/studentHistory.twig.html */
class __TwigTemplate_bc4e57b07bc66ed3c4c80cb9cfc074ca extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'tableInner' => [$this, 'block_tableInner'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 11
        return "components/dataTable.twig.html";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 12
        $macros["attendance"] = $this->macros["attendance"] = $this;
        // line 11
        $this->parent = $this->loadTemplate("components/dataTable.twig.html", "components/studentHistory.twig.html", 11);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 14
    public function block_tableInner($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 15
        yield "
    <div class=\"flex flex-wrap justify-center md:justify-between rounded bg-gray-100 border\">
        <div class=\"md:flex-1 p-4 text-sm text-gray-700\">
            <h3 class=\"mt-2 border-b-0\">
                ";
        // line 19
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Summary"), "html", null, true);
        yield "
            </h3>

            ";
        // line 22
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["summary"] ?? null), "total", [], "any", false, false, false, 22)) {
            // line 23
            yield "                ";
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["summary"] ?? null), "total", [], "any", false, false, false, 23) != ((CoreExtension::getAttribute($this->env, $this->source, ($context["summary"] ?? null), "present", [], "any", false, false, false, 23) + CoreExtension::getAttribute($this->env, $this->source, ($context["summary"] ?? null), "partial", [], "any", false, false, false, 23)) + CoreExtension::getAttribute($this->env, $this->source, ($context["summary"] ?? null), "absent", [], "any", false, false, false, 23)))) {
                // line 24
                yield "                    <div class=\"italic mb-4 text-xs\">
                    ";
                // line 25
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("It appears that this student is missing attendance data for some school days:"), "html", null, true);
                yield "
                    </div>
                ";
            }
            // line 28
            yield "
                <div class=\"leading-snug\">
                    <strong>";
            // line 30
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Total number of school days to date:"), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["summary"] ?? null), "total", [], "any", false, false, false, 30), "html", null, true);
            yield "</strong><br/>
                    ";
            // line 31
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Total number of school days attended:"), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((CoreExtension::getAttribute($this->env, $this->source, ($context["summary"] ?? null), "present", [], "any", false, false, false, 31) + CoreExtension::getAttribute($this->env, $this->source, ($context["summary"] ?? null), "partial", [], "any", false, false, false, 31)), "html", null, true);
            yield "<br/>
                    ";
            // line 32
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Total number of school days absent:"), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["summary"] ?? null), "absent", [], "any", false, false, false, 32), "html", null, true);
            yield "<br/>
                </div>
            ";
        }
        // line 35
        yield "        </div>

        ";
        // line 37
        if ( !($context["printView"] ?? null)) {
            // line 38
            yield "        <div class=\" p-4\">
            ";
            // line 39
            yield ($context["chart"] ?? null);
            yield "
        </div>
        ";
        }
        // line 42
        yield "    </div>


    <div id=\"studentHistory\">
    ";
        // line 46
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["dataSet"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["term"]) {
            // line 47
            yield "        <h4>
        ";
            // line 48
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["term"], "name", [], "any", false, false, false, 48), "html", null, true);
            yield "
        </h4>

        ";
            // line 51
            $context["daysOfWeek"] = CoreExtension::getAttribute($this->env, $this->source, $context["term"], "daysOfWeek", [], "any", false, false, false, 51);
            // line 52
            yield "        ";
            $context["blockWidth"] = ("w-1/" . Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["daysOfWeek"] ?? null)));
            // line 53
            yield "        ";
            $context["dayClass"] = "flex flex-col justify-center border-t border-b border-r py-2 px-1 -mt-px ";
            // line 54
            yield "
        <div class=\"flex flex-wrap border-t border-l border-gray-500\">

            ";
            // line 58
            yield "            <div class=\"w-full flex items-stretch text-xs text-center text-gray-700 font-bold bg-gray-200 border-b border-r border-gray-500\">
                ";
            // line 59
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["daysOfWeek"] ?? null));
            foreach ($context['_seq'] as $context["dayNameShort"] => $context["dayName"]) {
                // line 60
                yield "                    <div class=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["blockWidth"] ?? null), "html", null, true);
                yield " py-1\" title=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()($context["dayName"]), "html", null, true);
                yield "\">
                        ";
                // line 61
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()($context["dayNameShort"]), "html", null, true);
                yield "
                    </div>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['dayNameShort'], $context['dayName'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 64
            yield "
                ";
            // line 65
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["daysOfWeek"] ?? null));
            foreach ($context['_seq'] as $context["dayNameShort"] => $context["dayName"]) {
                // line 66
                yield "                    <div class=\"hidden md:block ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["blockWidth"] ?? null), "html", null, true);
                yield " py-1\" title=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()($context["dayName"]), "html", null, true);
                yield "\">
                        ";
                // line 67
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()($context["dayNameShort"]), "html", null, true);
                yield "
                    </div>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['dayNameShort'], $context['dayName'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 70
            yield "            </div>

            ";
            // line 73
            yield "            ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["term"], "weeks", [], "any", false, false, false, 73));
            foreach ($context['_seq'] as $context["weekNumber"] => $context["week"]) {
                // line 74
                yield "                <div class=\"w-full md:w-1/2 flex items-stretch text-xxs text-center text-gray-600\" style=\"min-height: 55px;\">

                ";
                // line 76
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable($context["week"]);
                foreach ($context['_seq'] as $context["_key"] => $context["day"]) {
                    // line 77
                    yield "                    ";
                    if (CoreExtension::getAttribute($this->env, $this->source, $context["day"], "outsideTerm", [], "any", false, false, false, 77)) {
                        // line 78
                        yield "                        <div class=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["blockWidth"] ?? null), "html", null, true);
                        yield " ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["dayClass"] ?? null), "html", null, true);
                        yield " bg-gray-400 border-gray-600 text-gray-500\">
                        </div>
                    ";
                    } elseif (CoreExtension::getAttribute($this->env, $this->source,                     // line 80
$context["day"], "beforeStartDate", [], "any", false, false, false, 80)) {
                        // line 81
                        yield "                        <div class=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["blockWidth"] ?? null), "html", null, true);
                        yield " ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["dayClass"] ?? null), "html", null, true);
                        yield " bg-gray-400 border-gray-600 text-gray-500\" title=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Before Start Date"), "html", null, true);
                        yield "\">
                            ";
                        // line 82
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "dateDisplay", [], "any", false, false, false, 82), "html", null, true);
                        yield "<br/>
                            ";
                        // line 83
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Before Start Date"), "html", null, true);
                        yield "
                        </div>
                    ";
                    } elseif (CoreExtension::getAttribute($this->env, $this->source,                     // line 85
$context["day"], "afterEndDate", [], "any", false, false, false, 85)) {
                        // line 86
                        yield "                        <div class=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["blockWidth"] ?? null), "html", null, true);
                        yield " ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["dayClass"] ?? null), "html", null, true);
                        yield " bg-gray-400 border-gray-600 text-gray-500\" title=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("After End Date"), "html", null, true);
                        yield "\">
                            ";
                        // line 87
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "dateDisplay", [], "any", false, false, false, 87), "html", null, true);
                        yield "<br/>
                            ";
                        // line 88
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("After End Date"), "html", null, true);
                        yield "
                        </div>
                    ";
                    } elseif (CoreExtension::getAttribute($this->env, $this->source,                     // line 90
$context["day"], "specialDay", [], "any", false, false, false, 90)) {
                        // line 91
                        yield "                        <div class=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["blockWidth"] ?? null), "html", null, true);
                        yield " ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["dayClass"] ?? null), "html", null, true);
                        yield " bg-gray-400 border-gray-600 text-gray-500\" title=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("School Closed"), "html", null, true);
                        yield "\">
                            ";
                        // line 92
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "dateDisplay", [], "any", false, false, false, 92), "html", null, true);
                        yield "<br/>
                            <b>";
                        // line 93
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "specialDay", [], "any", false, false, false, 93), "html", null, true);
                        yield "</b>
                        </div>
                    ";
                    } elseif ((Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source,                     // line 95
$context["day"], "logs", [], "any", false, false, false, 95)) && CoreExtension::getAttribute($this->env, $this->source, $context["day"], "offTimetable", [], "any", false, false, false, 95))) {
                        // line 96
                        yield "                        <div class=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["blockWidth"] ?? null), "html", null, true);
                        yield " ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["dayClass"] ?? null), "html", null, true);
                        yield " bg-blue-100 border-blue-700 text-blue-700\" title=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Off Timetable"), "html", null, true);
                        yield "\">
                            ";
                        // line 97
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "dateDisplay", [], "any", false, false, false, 97), "html", null, true);
                        yield "<br/>
                            <b>";
                        // line 98
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "offTimetable", [], "any", false, false, false, 98), "html", null, true);
                        yield "</b>
                        </div>
                    ";
                    } elseif ((Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source,                     // line 100
$context["day"], "logs", [], "any", false, false, false, 100)) && Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "classLogs", [], "any", false, false, false, 100)))) {
                        // line 101
                        yield "                        <div class=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["blockWidth"] ?? null), "html", null, true);
                        yield " ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["dayClass"] ?? null), "html", null, true);
                        yield " bg-gray-200 border-gray-600 text-gray-700\" title=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("No Data"), "html", null, true);
                        yield "\">
                            ";
                        // line 102
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "dateDisplay", [], "any", false, false, false, 102), "html", null, true);
                        yield "
                        </div>
                    ";
                    } else {
                        // line 105
                        yield "                        <a class=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["blockWidth"] ?? null), "html", null, true);
                        yield " ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["dayClass"] ?? null), "html", null, true);
                        yield " ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["day"], "endOfDay", [], "any", false, false, false, 105), "statusClass", [], "any", false, false, false, 105), "html", null, true);
                        yield " relative z-10\" x-tooltip.white=\"";
                        yield CoreExtension::callMacro($macros["attendance"], "macro_tooltip", [$context["day"]], 105, $context, $this->getSourceContext());
                        yield "\"
                            ";
                        // line 106
                        if (($context["canTakeAttendanceByPerson"] ?? null)) {
                            // line 107
                            yield "                                href=\"";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["absoluteURL"] ?? null), "html", null, true);
                            yield "/index.php?q=/modules/Attendance/attendance_take_byPerson.php&gibbonPersonID=";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "gibbonPersonID", [], "any", false, false, false, 107), "html", null, true);
                            yield "&currentDate=";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "date", [], "any", false, false, false, 107), "html", null, true);
                            yield "\"
                            ";
                        }
                        // line 108
                        yield ">

                            ";
                        // line 110
                        yield CoreExtension::callMacro($macros["attendance"], "macro_badge", [$context["day"]], 110, $context, $this->getSourceContext());
                        yield "

                            <span>";
                        // line 112
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "dateDisplay", [], "any", false, false, false, 112), "html", null, true);
                        yield "</span>
                            <span class=\"mt-1 font-bold\">";
                        // line 113
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["day"], "offTimetable", [], "any", false, false, false, 113)) ? ($this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "offTimetable", [], "any", false, false, false, 113))) : ($this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["day"], "endOfDay", [], "any", false, false, false, 113), "type", [], "any", false, false, false, 113)))), "html", null, true);
                        yield "</span>

                            ";
                        // line 115
                        if (($context["printView"] ?? null)) {
                            // line 116
                            yield "                                <span class=\"mt-1\">
                                ";
                            // line 117
                            $context['_parent'] = $context;
                            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["day"], "logs", [], "any", false, false, false, 117));
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
                            foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
                                // line 118
                                yield "                                    ";
                                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "code", [], "any", false, false, false, 118), "html", null, true);
                                // line 119
                                yield (( !CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 119)) ? (" : ") : (""));
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
                            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
                            $context = array_intersect_key($context, $_parent) + $_parent;
                            // line 121
                            yield "                                </span>
                            ";
                        }
                        // line 123
                        yield "                        </a>
                    ";
                    }
                    // line 125
                    yield "                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['day'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 126
                yield "            </div>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['weekNumber'], $context['week'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 128
            yield "        </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['term'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 130
        yield "    </div>


";
        return; yield '';
    }

    // line 139
    public function macro_tooltip($__day__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "day" => $__day__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 140
            yield "    <section class='w-64 -mx-2 p-4 rounded-md border text-center ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "endOfDay", [], "any", false, false, false, 140), "statusClass", [], "any", false, false, false, 140), "html", null, true);
            yield "'>
        ";
            // line 141
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "dateDisplay", [], "any", false, false, false, 141), "html", null, true);
            yield "<br/>
        
        <span class='font-bold text-base leading-normal'>";
            // line 143
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "endOfDay", [], "any", false, false, false, 143), "type", [], "any", false, false, false, 143)), "html", null, true);
            yield "</span><br/>

        ";
            // line 145
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "endOfDay", [], "any", false, false, false, 145), "reason", [], "any", false, false, false, 145)) {
                // line 146
                yield "            <span class='mt-1 text-xs'>";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "endOfDay", [], "any", false, false, false, 146), "reason", [], "any", false, false, false, 146)), "html", null, true);
                yield "</span><br/>
        ";
            }
            // line 148
            yield "
        <ul class='list-none ml-0 mt-4 text-xs text-left'>
            <li class='text-xxs  font-bold'>";
            // line 150
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("School Attendance"), "html", null, true);
            yield ":</li>
            ";
            // line 151
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "logs", [], "any", false, false, false, 151));
            $context['_iterated'] = false;
            foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
                // line 152
                yield "                <li class='";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "statusClass", [], "any", false, false, false, 152), "html", null, true);
                yield " leading-relaxed'>
                    ";
                // line 153
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "timestampTaken", [], "any", false, false, false, 153), ((($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "timestampTaken", [], "any", false, false, false, 153), "Y-m-d") == CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "date", [], "any", false, false, false, 153))) ? ("H:i") : ("H:i Y-m-d"))), "html", null, true);
                yield " -
                    ";
                // line 154
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "type", [], "any", false, false, false, 154)), "html", null, true);
                ((CoreExtension::getAttribute($this->env, $this->source, $context["log"], "reason", [], "any", false, false, false, 154)) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((", " . $this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "reason", [], "any", false, false, false, 154))), "html", null, true)) : (yield ""));
                yield " - 
                    ";
                // line 155
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["log"], "contextName", [], "any", false, false, false, 155)) ? (CoreExtension::getAttribute($this->env, $this->source, $context["log"], "contextName", [], "any", false, false, false, 155)) : ($this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "context", [], "any", false, false, false, 155)))), "html", null, true);
                yield "
                </li>
            ";
                $context['_iterated'] = true;
            }
            if (!$context['_iterated']) {
                // line 158
                yield "                <li class='text-xxs'>";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Not Available"), "html", null, true);
                yield "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 160
            yield "
        ";
            // line 161
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "classLogs", [], "any", false, false, false, 161)) {
                // line 162
                yield "            <li class='text-xxs  font-bold mt-2'>";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Class Attendance"), "html", null, true);
                yield ":</li>
            ";
                // line 163
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "classLogs", [], "any", false, false, false, 163));
                foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
                    // line 164
                    yield "                <li class='";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "statusClass", [], "any", false, false, false, 164), "html", null, true);
                    yield " leading-relaxed'>
                    ";
                    // line 165
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "timestampTaken", [], "any", false, false, false, 165), ((($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "timestampTaken", [], "any", false, false, false, 165), "Y-m-d") == CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "date", [], "any", false, false, false, 165))) ? ("H:i") : ("H:i Y-m-d"))), "html", null, true);
                    yield " -
                    ";
                    // line 166
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "type", [], "any", false, false, false, 166)), "html", null, true);
                    ((CoreExtension::getAttribute($this->env, $this->source, $context["log"], "reason", [], "any", false, false, false, 166)) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((", " . $this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "reason", [], "any", false, false, false, 166))), "html", null, true)) : (yield ""));
                    yield " - 
                    ";
                    // line 167
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["log"], "contextName", [], "any", false, false, false, 167)) ? (CoreExtension::getAttribute($this->env, $this->source, $context["log"], "contextName", [], "any", false, false, false, 167)) : ($this->env->getFunction('__')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["log"], "context", [], "any", false, false, false, 167)))), "html", null, true);
                    yield "
                </li>
            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 170
                yield "        ";
            }
            // line 171
            yield "    </section>
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 179
    public function macro_badge($__day__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "day" => $__day__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 180
            yield "    ";
            if ((((CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "absentCount", [], "any", false, false, false, 180) > 0) || (CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "partialCount", [], "any", false, false, false, 180) > 0)) && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "endOfDay", [], "any", false, false, false, 180), "status", [], "any", false, false, false, 180) == "present"))) {
                // line 181
                yield "    <div class=\"absolute top-0 right-0 mt-1 mr-1 z-10 rounded-full bg-gray-600 text-white no-underline leading-tight font-sans\" style=\"padding: 1px 3px; font-size: 8px\">
        ";
                // line 182
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "absentCount", [], "any", false, false, false, 182) + CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "partialCount", [], "any", false, false, false, 182)), "html", null, true);
                yield "
    </div>

    ";
            } elseif ((((CoreExtension::getAttribute($this->env, $this->source,             // line 185
($context["day"] ?? null), "presentCount", [], "any", false, false, false, 185) > 0) || (CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "partialCount", [], "any", false, false, false, 185) > 0)) && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "endOfDay", [], "any", false, false, false, 185), "status", [], "any", false, false, false, 185) == "absent"))) {
                // line 186
                yield "    <div class=\"absolute top-0 right-0 mt-1 mr-1 z-10 rounded-full bg-gray-600 text-white no-underline leading-tight font-sans\" style=\"padding: 1px 3px; font-size: 8px\">
        ";
                // line 187
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "presentCount", [], "any", false, false, false, 187) + CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "partialCount", [], "any", false, false, false, 187)), "html", null, true);
                yield "
    </div>

    ";
            } elseif ((((CoreExtension::getAttribute($this->env, $this->source,             // line 190
($context["day"] ?? null), "presentCount", [], "any", false, false, false, 190) > 0) || (CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "absentCount", [], "any", false, false, false, 190) > 0)) && (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "endOfDay", [], "any", false, false, false, 190), "status", [], "any", false, false, false, 190) == "partial"))) {
                // line 191
                yield "    <div class=\"absolute top-0 right-0 mt-1 mr-1 z-10 rounded-full bg-gray-600 text-white no-underline leading-tight font-sans\" style=\"padding: 1px 3px; font-size: 8px\">
        ";
                // line 192
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "presentCount", [], "any", false, false, false, 192) + CoreExtension::getAttribute($this->env, $this->source, ($context["day"] ?? null), "absentCount", [], "any", false, false, false, 192)), "html", null, true);
                yield "
    </div>
    ";
            }
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/studentHistory.twig.html";
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
        return array (  604 => 192,  601 => 191,  599 => 190,  593 => 187,  590 => 186,  588 => 185,  582 => 182,  579 => 181,  576 => 180,  564 => 179,  557 => 171,  554 => 170,  545 => 167,  540 => 166,  536 => 165,  531 => 164,  527 => 163,  522 => 162,  520 => 161,  517 => 160,  508 => 158,  500 => 155,  495 => 154,  491 => 153,  486 => 152,  481 => 151,  477 => 150,  473 => 148,  467 => 146,  465 => 145,  460 => 143,  455 => 141,  450 => 140,  438 => 139,  430 => 130,  423 => 128,  416 => 126,  410 => 125,  406 => 123,  402 => 121,  388 => 119,  385 => 118,  368 => 117,  365 => 116,  363 => 115,  358 => 113,  354 => 112,  349 => 110,  345 => 108,  335 => 107,  333 => 106,  322 => 105,  316 => 102,  307 => 101,  305 => 100,  300 => 98,  296 => 97,  287 => 96,  285 => 95,  280 => 93,  276 => 92,  267 => 91,  265 => 90,  260 => 88,  256 => 87,  247 => 86,  245 => 85,  240 => 83,  236 => 82,  227 => 81,  225 => 80,  217 => 78,  214 => 77,  210 => 76,  206 => 74,  201 => 73,  197 => 70,  188 => 67,  181 => 66,  177 => 65,  174 => 64,  165 => 61,  158 => 60,  154 => 59,  151 => 58,  146 => 54,  143 => 53,  140 => 52,  138 => 51,  132 => 48,  129 => 47,  125 => 46,  119 => 42,  113 => 39,  110 => 38,  108 => 37,  104 => 35,  96 => 32,  90 => 31,  84 => 30,  80 => 28,  74 => 25,  71 => 24,  68 => 23,  66 => 22,  60 => 19,  54 => 15,  50 => 14,  45 => 11,  43 => 12,  36 => 11,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/studentHistory.twig.html", "/var/www/html/gibbon/resources/templates/components/studentHistory.twig.html");
    }
}
