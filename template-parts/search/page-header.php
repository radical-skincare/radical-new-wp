
<?php
$search_post_type = $_GET['post_type'];
?>
<div class="row mb-5">
  <div class="col">
    <h2 class="mb-3">Search Results</h2>
    <form action="<?php echo esc_html($site_url); ?>" method="get" class="row">
      <div class="col-md-4 mb-3 mb-md-0">
        <select class="form-control" id="post_type" name="post_type" onchange="this.form.submit()">
          <option value="" <?php echo !isset($search_post_type) && $search_post_type === '' ? 'selected' : ''; ?>>All</option>
          <option value="product" <?php echo isset($search_post_type) && $search_post_type === 'product' ? 'selected' : ''; ?>>Product</option>
          <option value="podcasts" <?php echo isset($search_post_type) && $search_post_type === 'podcasts' ? 'selected' : ''; ?>>Podcast</option>
          <option value="press_item" <?php echo isset($search_post_type) && $search_post_type === 'press_item' ? 'selected' : ''; ?>>Press</option>
          <option value="post" <?php echo isset($search_post_type) && $search_post_type === 'post' ? 'selected' : ''; ?>>Post</option>
          <option value="page" <?php echo isset($search_post_type) && $search_post_type === 'page' ? 'selected' : ''; ?>>Page</option>
        </select>
      </div>
      <div class="col-md-4 offset-md-4">
        <label for="search" class="d-none">Search</label>
        <div class="input-group mb-3">
          <input id="search" type="text" name="s" class="form-control" value="<?php echo esc_html($_GET['s']); ?>" placeholder="Search..."/>
          <div class="input-group-append">
            <button type="submit" class="input-group-text">
              <i class="fa fa-search" aria-hidden="true"></i>
              <span class="sr-only">Search</span>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
