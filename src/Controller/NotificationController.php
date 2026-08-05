<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/notifications')]
#[IsGranted('ROLE_USER')]
class NotificationController extends AbstractController
{
    #[Route('/api/unread', name: 'app_notifications_unread_api', methods: ['GET'])]
    public function getUnreadNotifications(NotificationRepository $notificationRepository): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $notifications = $notificationRepository->findBy(
            ['user' => $user, 'isRead' => false],
            ['createdAt' => 'DESC']
        );

        $data = [];
        foreach ($notifications as $notification) {
            $data[] = [
                'id' => $notification->getId(),
                'title' => $notification->getTitle(),
                'message' => $notification->getMessage(),
                'createdAt' => $notification->getCreatedAt()->format('Y-m-d H:i'),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/{id}/read', name: 'app_notifications_mark_read', methods: ['POST'])]
    public function markAsRead(
        Notification $notification,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        /** @var User|null $user */
        $user = $this->getUser();
        if ($notification->getUser() !== $user) {
            throw $this->createAccessDeniedException('You cannot modify this notification.');
        }

        $notification->setIsRead(true);
        $entityManager->flush();

        if ($request->isXmlHttpRequest() || str_contains((string) $request->headers->get('Accept'), 'application/json')) {
            return new JsonResponse(['success' => true]);
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?? $this->generateUrl('app_employee_deshboard'));
    }

    #[Route('/read-all', name: 'app_notifications_mark_all_read', methods: ['POST'])]
    public function markAllAsRead(
        NotificationRepository $notificationRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $unreadNotifications = $notificationRepository->findBy(['user' => $user, 'isRead' => false]);
        foreach ($unreadNotifications as $notification) {
            $notification->setIsRead(true);
        }
        $entityManager->flush();

        if ($request->isXmlHttpRequest() || str_contains((string) $request->headers->get('Accept'), 'application/json')) {
            return new JsonResponse(['success' => true]);
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?? $this->generateUrl('app_employee_deshboard'));
    }
}
