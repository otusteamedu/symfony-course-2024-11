<?php

namespace App\Controller;

use App\Domain\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WorldController extends AbstractController
{
    public function __construct(
        private readonly MessageService $messageService,
    )
    {
    }

    public function hello(): Response
    {
        $result = $this->messageService->printMessages('world');

        return new Response("<html><body>$result</body></html>");
    }
}
