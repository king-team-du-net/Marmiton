<?php

namespace App\EventSubscriber\Article;

use App\Entity\Blog\Article;
use App\Entity\Blog\Comment;
use App\Entity\User;
use App\Events\Article\CommentCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class NotificationCommentsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator,
        private readonly string $sender
    ) {
    }

    public function onCommentCreated(CommentCreatedEvent $event): void
    {
        /** @var Comment $comment */
        $comment = $event->getComment();

        /** @var Article $article */
        $article = $comment->getArticle();

        /** @var User $author */
        $author = $article->getAuthor();

        /** @var string $emailAddress */
        $emailAddress = $author->getEmail();

        $UrlToArticle = $this->urlGenerator->generate('blog_show', [
            'slug' => $article->getSlug(),
            '_fragment' => 'comment_'.$comment->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $subject = $this->translator->trans('Your article received a comment!');
        $body = $this->translator->trans("Your article {$article->getTitle()} has received a new comment. You can read the comment by following", [
            'title' => $article->getTitle(),
            'url' => $UrlToArticle,
        ]);

        $email = (new Email())
            ->from($this->sender)
            ->to($emailAddress)
            ->subject($subject)
            ->html($body)
        ;

        $this->mailer->send($email);
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CommentCreatedEvent::class => 'onCommentCreated',
        ];
    }
}
