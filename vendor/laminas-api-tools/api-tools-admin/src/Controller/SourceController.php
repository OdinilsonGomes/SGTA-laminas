<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Admin\Controller;

use Laminas\ApiTools\Admin\Model\ModuleModel;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\ApiProblem\View\ApiProblemModel;
use Laminas\ApiTools\ContentNegotiation\ViewModel;
use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use ReflectionClass;
use ReflectionException;

use function class_exists;
use function count;
use function explode;
use function highlight_file;
use function sprintf;
use function str_pad;
use function strlen;
use function strval;
use function substr;
use function urldecode;

use const STR_PAD_LEFT;

class SourceController extends AbstractActionController
{
    /** @var ModuleModel */
    protected $moduleModel;

    public function __construct(ModuleModel $moduleModel)
    {
        $this->moduleModel = $moduleModel;
    }

    /**
     * @return ApiProblemModel|ViewModel
     * @throws ReflectionException
     */
    public function sourceAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        switch ($request->getMethod()) {
            case $request::METHOD_GET:
                $module = urldecode($this->params()->fromQuery('module', false));
                if (! $module) {
                    return new ApiProblemModel(
                        new ApiProblem(
                            422,
                            'Module parameter not provided',
                            'https://tools.ietf.org/html/rfc4918',
                            'Unprocessable Entity'
                        )
                    );
                }
                $result = $this->moduleModel->getModule($module);
                if (! $result) {
                    return new ApiProblemModel(
                        new ApiProblem(
                            422,
                            'The module specified doesn\'t exist',
                            'https://tools.ietf.org/html/rfc4918',
                            'Unprocessable Entity'
                        )
                    );
                }

                $class = urldecode($this->params()->fromQuery('class', false));
                if (! $class) {
                    return new ApiProblemModel(
                        new ApiProblem(
                            422,
                            'Class parameter not provided',
                            'https://tools.ietf.org/html/rfc4918',
                            'Unprocessable Entity'
                        )
                    );
                }
                if (! class_exists($class)) {
                    return new ApiProblemModel(
                        new ApiProblem(
                            422,
                            'The class specified doesn\'t exist',
                            'https://tools.ietf.org/html/rfc4918',
                            'Unprocessable Entity'
                        )
                    );
                }

                $reflector = new ReflectionClass($class);
                $fileName  = $reflector->getFileName();

                $metadata = [
                    'module' => $module,
                    'class'  => $class,
                    'file'   => $fileName,
                    'source' => $this->highlightFileWithNum($fileName),
                ];

                $model = new ViewModel($metadata);
                $model->setTerminal(true);
                return $model;

            default:
                return new ApiProblemModel(
                    new ApiProblem(405, 'Only the method PUT is allowed for this URI')
                );
        }
    }

    /**
     * Highlight a PHP source code with line numbers
     *
     * @param  string $file
     * @return string
     */
    protected function highlightFileWithNum($file)
    {
        $code      = substr(highlight_file($file, true), 36, -15);
        $lines     = explode('<br />', $code);
        $lineCount = count($lines);
        $padLength = strlen((string) $lineCount);
        $code      = '<code><span style="color: #000000">';
        foreach ($lines as $i => $line) {
            $lineNumber = str_pad(strval($i + 1), $padLength, '0', STR_PAD_LEFT);
            $code      .= sprintf('<br /><span style="color: #999999">%s  </span>%s', $lineNumber, $line);
        }
        $code .= '</span></code>';
        return $code;
    }

    /**
     * Set the request object manually
     *
     * Provided for testing.
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }
}
