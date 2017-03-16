<div class="box box-success">
    <!--<div class="box-header"></div>-->
    <div class="box-body">
        <table id="example1" class="table table-bordered">
            <thead>
            <tr class="success">
                <?php foreach( $listing_cols as $col ):?>
                <th><?php $module->fields[$col]['label'] or ucfirst($col) ?></th>
                <?php endforeach; ?>

            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>