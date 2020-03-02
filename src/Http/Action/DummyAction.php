<?php declare(strict_types=1);
namespace App\Http\Action;

use App\Http\Request\DummyRequest;
use Symfony\Component\HttpFoundation\Response;

final class DummyAction extends BaseAction
{
    public function execute(DummyRequest $request): Response
    {
        return $this->render('dummy.html.twig', [
            'foo' => $request->getFoo(),
        ]);
    }
}