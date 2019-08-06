<?php

declare(strict_types=1);

namespace AppTest\Handler;

use App\Handler\HomePageHandler;
use App\Filters;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Twig\TwigRenderer;
use Aptoma\Twig\Extension\MarkdownEngine;

class HomePageHandlerTest extends TestCase
{

    private $pdo;
    private $router;
    private $template;

    protected function setUp()
    {
        $this->pdo = new \PDO(
            "mysql:host=localhost;port=3306;dbname=klingogbang",
            'root',
            '',
            [
                \PDO::MYSQL_ATTR_INIT_COMMAND =>
                    "SET NAMES 'utf8', ".
                    "sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'",
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            ]
        );
        $this->router = new \Zend\Expressive\Router\ZendRouter();

        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../src/App/templates');
        $environment = new \Twig\Environment($loader, []);
        $environment->setExtensions([
            \Aptoma\Twig\Extension\MarkdownExtension::class => new \Aptoma\Twig\Extension\MarkdownExtension(
                new MarkdownEngine\MichelfMarkdownEngine()
            ),
            Filters\Slug::class => new Filters\Slug(),
        ]);

        $function = new \Twig\TwigFunction('path', function ($path) {
            return $path;
        });
        $environment->addFunction($function);
        $this->template = new TwigRenderer($environment, 'html.twig');
        $this->template->addPath(__DIR__ . '/../../../src/App/templates/app', 'app');
        $this->template->addPath(__DIR__ . '/../../../src/App/templates/error', 'error');
        $this->template->addPath(__DIR__ . '/../../../src/App/templates/layout', 'layout');
        $this->template->addPath(__DIR__ . '/../../../src/App/templates/partials', 'partials');
    }

    public function testFactoryWithTemplate()
    {
        $service = new class extends \App\Service\Entry {
            function __construct()
            {
            }

            function fetchByDate(\DateTime $date): array {
                return [
                    [
                        'id' => '1',
                        'title' => 'Title 1',
                        'from' => '2001-01-01',
                        'to' => '2001-01-01',
                        'created' => '2001-01-01',
                        'affected' => '2001-01-01',
                        'type' => 'show',
                        'body' => 'body 1',
                        'authors' => [
                            [
                                'id' => '1',
                                'name' => 'Author name 1',
                                'created' => '2001-01-01',
                                'affected' => '2001-01-01',
                            ]
                        ],
                        'poster' => [
                            'id' => '',
                            'name' => 'some-name.jpg',
                            'description' => '',
                            'size' => '',
                            'width' => '',
                            'height' => '',
                            'created' => '',
                            'affected' => '',
                        ],
                        'gallery' => [
                            [
                                'id' => '',
                                'name' => 'some-name.jpg',
                                'description' => '',
                                'size' => '',
                                'width' => '',
                                'height' => '',
                                'created' => '',
                                'affected' => '',
                            ]
                        ],
                    ],
                    [
                        'id' => '2',
                        'title' => 'title 2',
                        'from' => '2001-01-01',
                        'to' => '2001-01-01',
                        'created' => '2001-01-01',
                        'affected' => '2001-01-01',
                        'type' => 'news',
                        'body' => 'body 2',
                        'authors' => [
                            [
                                'id' => '1',
                                'name' => 'Author Name 2',
                                'created' => '2001-01-01',
                                'affected' => '2001-01-01',
                            ]
                        ],
                        'poster' => [
                            'id' => '',
                            'name' => 'some-name.jpg',
                            'description' => '',
                            'size' => '',
                            'width' => '',
                            'height' => '',
                            'created' => '',
                            'affected' => '',
                        ],
                        'gallery' => [
                            [
                                'id' => '',
                                'name' => 'some-name.jpg',
                                'description' => '',
                                'size' => '',
                                'width' => '',
                                'height' => '',
                                'created' => '',
                                'affected' => '',
                            ]
                        ],
                    ]
                ];
            }

            function fetchAfter(\DateTime $date): array {
                return [
                    [
                        'id' => '2',
                        'title' => 'title 2',
                        'from' => '2001-01-01',
                        'to' => '2001-01-01',
                        'created' => '2001-01-01',
                        'affected' => '2001-01-01',
                        'type' => 'show',
                        'body' => 'body 2'
                    ]
                ];
            }
        };
        $handler = new HomePageHandler(
            $this->router,
            $service,
            $this->template
        );

        $response = $handler->handle( new \Zend\Diactoros\ServerRequest());

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        echo $response->getBody()->getContents();
    }
}
