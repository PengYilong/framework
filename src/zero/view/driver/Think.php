<?php
namespace zero\view\driver;

class Think
{
    protected $template;
    protected $config = [];

    public function __construct(App $app, $config = [])
    {

        if( empty($this->config['view_path']) ) {
            $this->config['view_path'] = $app->getModulePath() . 'view' . DIRECTORY_SEPARATOR;
        }

        $this->template = new Template($this->config);
    }

    public function fetch(string $template, array $data = [])
    {
        if( '' == pathinfo($template, PATHINFO_EXTENSION) ) {
            $template = $this->parseTemplate($template);
        }

        if( !is_file($template) ) {
            throw new TemplateNotFoundException('The template doesn\'t exist: '. $template, $template);
        }

        $this->template->fetch($template, $data);
    }

    /**
     * Undocumented function
     *
     * @param string $template
     * @return string
     */
    public function parseTemplate(string $template): string
    {
        $request = $this->app['request'];

        $path = $this->config['view_path'];

        $depr = $this->config['view_depr'];

        if( 0 !== strpos($template, '/') ) {
            $template = str_replace(['/', ':'], $depr, $template);
            $controller = $request($request->controller());
            if($controller) {
                if('' == $template) {
                    $template = $controller. $depr . $request->action();
                } else if(false === strpos($template, $depr)) {
                    $template = $controller . $depr . $template;
                }
            }
        } else {
            $template = $depr . substr($template, 1);
        }

        return $path;
    }

}