<div class="admin-box">
        <h3>Park allocations</h3>

       
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Bay</th>
                        <th>Allocated to</th>
                        <th>Date</th>
                        <th>Phone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                
                <tbody>
                <?php if (isset($data) && is_array($data)) :?>
                    <?php foreach ($data as $d) :    $pp = $this->user_model->find_meta_for($d->id, array('phone')) ?>
                    <tr>
                        <td>
                             <?php e($d->baylocation); ?>
                        </td>
                        <td>
                             <?php e($d->user); ?>
                        </td>
                        <td>
                             <?php e($d->datefree); ?>
                        </td>
                         <td>
                             <?php e($pp->phone); ?>
                        </td>
                        <td>
                             <?php echo mailto($d->email); ?>
                           
                        </td>
                       
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            <br/>
                            <div class="alert alert-warning">
                                No Allocations found.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

    </div>