<?php

namespace App\Controller;

use Psr\{
    Http\Message\ResponseInterface as Response,
    Http\Message\ServerRequestInterface as Request
};

use Slim\{
    App,
    Routing\RouteContext,
    Views\PhpRenderer
};

use App\{
    Helper\Session
};

use App\{
    Model\LivroDAO,
    Model\LivroAutor,
    Model\LivroAutorDAO,
    Model\LivroEditora,
    Model\LivroEditoraDAO,
    Model\EntradaDAO,
    Model\SaidaDAO
};

use PDO;

class DashboardController extends GestorController
{
    private
        $app, $container, $database, $renderer;

    private
        $livroDAO, $livroAutor, $livroAutorDAO, $livroEditora, $livroEditoraDAO, $entradaDAO, $saidaDAO;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $this->app->getContainer();
        $this->database = $this->container->get(PDO::class);
        $this->renderer = $this->container->get(PhpRenderer::class);

        $this->livroDAO = new LivroDAO($this->database);
        $this->livroAutor = new LivroAutor();
        $this->livroAutorDAO = new LivroAutorDAO($this->database);
        $this->livroEditora = new LivroEditora();
        $this->livroEditoraDAO = new LivroEditoraDAO($this->database);
        $this->entradaDAO = new EntradaDAO($this->database);
        $this->saidaDAO = new SaidaDAO($this->database);

        parent::__construct($this->app);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $basePath = '/' . $this->container->get('settings')['api']['path'];

        $gestor = parent::getGestor();
        $gestor = parent::applyGestorData($gestor);

        if ($gestor === []) {
            Session::destroy();

            $url = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('login');

            return $response
                ->withHeader('Location', $url)
                ->withStatus(302);
        }

        $livros = $this->livroDAO->getRecentes();

        foreach ($livros as $key => $value) {
            $this->livroAutor->setIDLivro($livros[$key]['ID']);
            $autor['items'] = $this->livroAutorDAO->getAutorByIDLivro($this->livroAutor);
            $livros[$key]['autor'] = self::autoresOrEditorasToString($autor['items']);

            $this->livroEditora->setIDLivro($livros[$key]['ID']);
            $editora['items'] = $this->livroEditoraDAO->getEditoraByIDLivro($this->livroEditora);
            $livros[$key]['editora'] = self::autoresOrEditorasToString($editora['items']);
        }

        $totalLivros = $this->livroDAO->getTotalRegisters()[0]['total_registros'];

        $totalEntradas = $this->entradaDAO->getTotalEntradaQuantidade()[0]['total_quantidade'];
        $totalEntradas = ($totalEntradas == null) ? 0 : $totalEntradas;

        $totalSaidas = $this->saidaDAO->getTotalSaidaQuantidade()[0]['total_quantidade'];
        $totalSaidas = ($totalSaidas == null) ? 0 : $totalSaidas;

        $templateVariables = [
            'basePath' => $basePath,
            'gestor' => $gestor,
            'livros' => $livros,
            'totalLivros' => $totalLivros,
            'totalEntradas' => $totalEntradas,
            'totalSaidas' => $totalSaidas
        ];

        return $this->renderer->render($response, 'dashboard/index.php', $templateVariables);
    }

    public function logout(Request $request, Response $response, array $args): Response
    {
        Session::destroy();

        $url = RouteContext::fromRequest($request)
            ->getRouteParser()
            ->urlFor('login');

        return $response
            ->withHeader('Location', $url)
            ->withStatus(302);
    }

    private static function autoresOrEditorasToString($array)
    {
        $string = '';

        foreach ($array as $item) {
            $string .= $item['nome'] . ', ';
        }

        $string = rtrim($string, ', ');

        return $string;
    }
}
