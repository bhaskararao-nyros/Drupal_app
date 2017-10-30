<?php

/* sites/all/modules/views_slideshow/templates/views-view-slideshow.html.twig */
class __TwigTemplate_048946d9e3397d6895fb3c17238603300b55ee14befd7958008cd539be4eb9af extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $tags = array("if" => 17);
        $filters = array();
        $functions = array();

        try {
            $this->env->getExtension('Twig_Extension_Sandbox')->checkSecurity(
                array('if'),
                array(),
                array()
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 17
        if (($context["slideshow"] ?? null)) {
            // line 18
            echo "  <div class=\"skin-";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["skin"] ?? null), "html", null, true));
            echo "\">
    ";
            // line 19
            if (($context["top_widget_rendered"] ?? null)) {
                // line 20
                echo "      <div class=\"views-slideshow-controls-top clearfix\">
        ";
                // line 21
                echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["top_widget_rendered"] ?? null), "html", null, true));
                echo "
      </div>
    ";
            }
            // line 24
            echo "
    ";
            // line 25
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["slideshow"] ?? null), "html", null, true));
            echo "

    ";
            // line 27
            if (($context["bottom_widget_rendered"] ?? null)) {
                // line 28
                echo "      <div class=\"views-slideshow-controls-bottom clearfix\">
        ";
                // line 29
                echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["bottom_widget_rendered"] ?? null), "html", null, true));
                echo "
      </div>
    ";
            }
            // line 32
            echo "    </div>
";
        }
        // line 34
        echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script>";
    }

    public function getTemplateName()
    {
        return "sites/all/modules/views_slideshow/templates/views-view-slideshow.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  84 => 34,  80 => 32,  74 => 29,  71 => 28,  69 => 27,  64 => 25,  61 => 24,  55 => 21,  52 => 20,  50 => 19,  45 => 18,  43 => 17,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "sites/all/modules/views_slideshow/templates/views-view-slideshow.html.twig", "/var/www/html/drupal-8.4.0/sites/all/modules/views_slideshow/templates/views-view-slideshow.html.twig");
    }
}
