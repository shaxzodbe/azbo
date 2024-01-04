

<?php $__env->startSection('content'); ?>
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">
                <?php echo $__env->make('frontend.inc.user_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="aiz-user-panel">
                    <div class="aiz-titlebar mt-2 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="h3"><?php echo e(translate('Support Ticket')); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mx-auto mb-3" >
                            <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition" data-toggle="modal" data-target="#ticket_modal">
                                <span class="size-70px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                                    <i class="las la-plus la-3x text-white"></i>
                                </span>
                                <div class="fs-20 text-primary"><?php echo e(translate('Create a Ticket')); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6"><?php echo e(translate('Tickets')); ?></h5>
                        </div>
                          <div class="card-body">
                              <table class="table aiz-table mb-0">
                                  <thead>
                                      <tr>
                                          <th data-breakpoints="lg"><?php echo e(translate('Ticket ID')); ?></th>
                                          <th data-breakpoints="lg"><?php echo e(translate('Sending Date')); ?></th>
                                          <th><?php echo e(translate('Subject')); ?></th>
                                          <th><?php echo e(translate('Status')); ?></th>
                                          <th data-breakpoints="lg"><?php echo e(translate('Options')); ?></th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <tr>
                                              <td>#<?php echo e($ticket->code); ?></td>
                                              <td><?php echo e($ticket->created_at); ?></td>
                                              <td><?php echo e($ticket->subject); ?></td>
                                              <td>
                                                  <?php if($ticket->status == 'pending'): ?>
                                                      <span class="badge badge-inline badge-danger"><?php echo e(translate('Pending')); ?></span>
                                                  <?php elseif($ticket->status == 'open'): ?>
                                                      <span class="badge badge-inline badge-secondary"><?php echo e(translate('Open')); ?></span>
                                                  <?php else: ?>
                                                      <span class="badge badge-inline badge-success"><?php echo e(translate('Solved')); ?></span>
                                                  <?php endif; ?>
                                              </td>
                                              <td>
                                                  <a href="<?php echo e(route('support_ticket.show', encrypt($ticket->id))); ?>" class="btn btn-styled btn-link py-1 px-0 icon-anim text-underline--none">
                                                      <?php echo e(translate('View Details')); ?>

                                                      <i class="la la-angle-right text-sm"></i>
                                                  </a>
                                              </td>
                                          </tr>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </tbody>
                              </table>
                              <div class="aiz-pagination">
                                  <?php echo e($tickets->links()); ?>

                              </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
<div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                  <h5 class="modal-title strong-600 heading-5"><?php echo e(translate('Create a Ticket')); ?></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body px-3 pt-3">
                  <form class="" action="<?php echo e(route('support_ticket.store')); ?>" method="post" enctype="multipart/form-data">
                      <?php echo csrf_field(); ?>
                      <div class="row">
                          <div class="col-md-2">
                              <label><?php echo e(translate('Subject')); ?></label>
                          </div>
                          <div class="col-md-10">
                              <input type="text" class="form-control mb-3" placeholder="<?php echo e(translate('Subject')); ?>" name="subject" required>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-2">
                              <label><?php echo e(translate('Provide a detailed description')); ?></label>
                          </div>
                          <div class="col-md-10">
                              <textarea type="text" class="form-control mb-3" rows="3" name="details" placeholder="<?php echo e(translate('Type your reply')); ?>" data-buttons="bold,underline,italic,|,ul,ol,|,paragraph,|,undo,redo" required></textarea>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-md-2 col-form-label"><?php echo e(translate('Photo')); ?></label>
                          <div class="col-md-10">
                              <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                  <div class="input-group-prepend">
                                      <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
                                  </div>
                                  <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
                                  <input type="hidden" name="attachments" class="selected-files">
                              </div>
                              <div class="file-preview box sm">
                              </div>
                          </div>
                      </div>
                      <div class="text-right mt-4">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(translate('cancel')); ?></button>
                          <button type="submit" class="btn btn-primary"><?php echo e(translate('Send Ticket')); ?></button>
                      </div>
                  </form>
              </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/azbo/resources/views/frontend/user/support_ticket/index.blade.php ENDPATH**/ ?>