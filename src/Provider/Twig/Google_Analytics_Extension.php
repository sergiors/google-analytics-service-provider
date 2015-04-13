<?php
namespace Inbep\Silex\Provider\Twig;

class Google_Analytics_Extension extends \Twig_Extension
{
    protected $twig;

    protected $code;

    /**
     * @param
     * @param string [$code]
     */
    public function __construct($twig, $code = null)
    {
        $this->twig = $twig;
        $this->code = $code;
    }

    public function getFunctions()
    {
        return array(
            'ga' => new \Twig_Function_Method($this, 'getGa', array(
                'is_safe' => array('html')
            ))
        );
    }

    public function getGa()
    {
        if (empty($this->code)) {
            return null;
        }

        return $this->twig->render('_ga.twig', array('code' => $this->code));
    }

    public function getName()
    {
        return 'ga';
    }
}
