<div class="modal" id="editProductModal<?php echo $product_id; ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-box"></i> Edit Product</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="post.php" method="post" autocomplete="off">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <div class="modal-body">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $product_name; ?>" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <input type="text" class="form-control" name="description" value="<?php echo $product_description; ?>">
          </div>
          <div class="form-group">
            <label>Cost</label>
            <input type="text" class="form-control" name="cost" value="<?php echo $product_cost; ?>">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" name="edit_product" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>