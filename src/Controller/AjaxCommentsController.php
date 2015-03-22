<?php

/**
 * @file
 * Contains \Drupal\ajax_comments\Controller\AjaxCommentsController.
 */

namespace Drupal\ajax_comments\Controller;

use Drupal\ajax_comments\Ajax\ajaxCommentsScrollToElementCommand;
use Drupal\comment\CommentInterface;
use Drupal\Core\Ajax\AfterCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller routines for AJAX comments routes.
 */
class AjaxCommentsController extends ControllerBase {

  /**
   * Constructs a AjaxCommentsController object.
   *
   * @todo Add needed services.
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
    );
  }

  /**
   * Returns the comment edit form.
   *
   * @param \Drupal\comment\CommentInterface $comment
   *   The comment entity.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   The Ajax response.
   */
  public function edit(CommentInterface $comment) {
    $response = new AjaxResponse();
    // Remove anchor.
    $response->addCommand(new RemoveCommand('a#comment-' . $comment->id()));

    // Replace comment with form.
    $form = $this->entityFormBuilder()->getForm($comment);
    $response->addCommand(new ReplaceCommand('.comment-wrapper-' . $comment->id(), $form));

    if (\Drupal::config('ajax_comments.settings')->get('enable_scroll')) {
      $response->addCommand(new ajaxCommentsScrollToElementCommand('.ajax-comments-reply-form-' . $comment->getCommentedEntityId() . '-' . $comment->get('pid')->target_id . '-' . $comment->id()));
    }

    return $response;
  }

  /**
   * Returns comment delete form.
   *
   * @param \Drupal\comment\CommentInterface $comment
   *   The comment entity.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   The Ajax response.
   */
  public function delete(CommentInterface $comment) {
    $response = new AjaxResponse();
    // Hide contents.
    $response->addCommand(new InvokeCommand('.comment-wrapper-' . $comment->id() . ' >*', 'hide'));

    // Replace comment with form.
    $form = $this->entityFormBuilder()->getForm($comment, 'delete');
    $response->addCommand(new ReplaceCommand('.comment-wrapper-' . $comment->id(), $form));

    return $response;
  }

  /**
   * Returns comment reply form.
   *
   * @param \Drupal\comment\CommentInterface $comment
   *   The comment entity.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   The Ajax response.
   *
   * @see \Drupal\comment\Controller\CommentController::getReplyForm()
   */
  public function reply(CommentInterface $comment) {
    $response = new AjaxResponse();
    // @todo Check access.
    // @todo Move remains of ajax_comments_reply() here.
    // @todo Decide how to add initial comment.

    // Add new comment form.
    $new_comment = $this->entityManager()->getStorage('comment')->create([
      'entity_id' => $comment->getCommentedEntityId(),
      'entity_type' => $comment->getCommentedEntityTypeId(),
      'field_name' => $comment->getFieldName(),
      'pid' => $comment->id(),
    ]);
    $form = $this->entityFormBuilder()->getForm($new_comment);
    $response->addCommand(new AfterCommand('.comment-wrapper-' . $comment->id(), $form));

    return $response;
  }

}
