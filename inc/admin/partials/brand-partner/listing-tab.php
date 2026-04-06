<?php
/*
 * This file creates the page:
 * /options-general.php?page=radical-rx&tab=ambassador
 *
*/

// if( ! class_exists( 'WP_List_Table' ) ) {
//     require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
// }

// class My_List_Table extends WP_List_Table {
// }

// $myListTable = new My_List_Table();

// $example_data = array(
//   array('ID' => 1,'booktitle' => 'Quarter Share', 'author' => 'Nathan Lowell',
//         'isbn' => '978-0982514542'),
//   array('ID' => 2, 'booktitle' => '7th Son: Descent','author' => 'J. C. Hutchins',
//         'isbn' => '0312384378'),
//   array('ID' => 3, 'booktitle' => 'Shadowmagic', 'author' => 'John Lenahan',
//         'isbn' => '978-1905548927'),
//   array('ID' => 4, 'booktitle' => 'The Crown Conspiracy', 'author' => 'Michael J. Sullivan',
//         'isbn' => '978-0979621130'),
//   array('ID' => 5, 'booktitle'     => 'Max Quick: The Pocket and the Pendant', 'author'    => 'Mark Jeffrey',
//         'isbn' => '978-0061988929'),
//   array('ID' => 6, 'booktitle' => 'Jack Wakes Up: A Novel', 'author' => 'Seth Harwood',
//         'isbn' => '978-0307454355')
// );


// function get_columns(){
//   $columns = array(
//     'booktitle' => 'Title',
//     'author'    => 'Author',
//     'isbn'      => 'ISBN'
//   );
//   return $columns;
// }

// function prepare_items() {
//   $columns = $this->get_columns();
//   $hidden = array();
//   $sortable = array();
//   $this->_column_headers = array($columns, $hidden, $sortable);
//   $this->items = $this->example_data;;
// }

// function column_default( $item, $column_name ) {
//   switch( $column_name ) { 
//     case 'booktitle':
//     case 'author':
//     case 'isbn':
//       return $item[ $column_name ];
//     default:
//       return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
//   }
// }

// function my_render_list_page(){
//   $myListTable = new My_List_Table();
//   echo '<div class="wrap"><h2>My List Table Test</h2>'; 
//   $myListTable->prepare_items(); 
//   $myListTable->display(); 
//   echo '</div>'; 
// }

// my_render_list_page();

$site_url = get_site_url();
?>

<style>
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}
.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: white;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table td, .table th {
    padding: .75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
</style>

<h3>Brand Partners</h3>

<div class="table-responsive">
	<table id="ambassadors" class="table">
		<thead>
			<tr>
			  <th scope="col">#</th>
			  <th scope="col">User ID</th>
			  <th scope="col">Affiliate ID</th>
			  <th scope="col">Name</th>
        <th scope="col">Site</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<script>
let allAmbassadors

function get_All_Users() {
  const ASYNC_TIMEOUT = 10000 // 10 secs
  let dfd = jQuery.Deferred()
  if (allAmbassadors) {
      dfd.resolve( "success" )
  } else {
    let options = {
      type: "GET",
      url:  "<?php echo $site_url; ?>/wp-json/affservices/v1/ambassadors",
      // "http://localhost:8888/radicalskincare/wp-json/affwp/v1/affiliates"
      cache: false,
      timeout: ASYNC_TIMEOUT, // 10 secs
    }
    jQuery.ajax( options ).done(function( data ) {
      allAmbassadors = data
      dfd.resolve( 'success' )
    }).fail(function(error) {
      dfd.reject( error )
    })
  }
  return dfd.promise()
}

function initPage() {
  jQuery.when( get_All_Users() ).then( function( status ) {
    // console.log('allCoaches', allAmbassadors);
    let i = 1;
    allAmbassadors.forEach( function(ambassador) {
      let html = `
        <tr class="ambassador">
          <th scope="row">${i}</th>
          <td>
            <a href="<?php echo $site_url; ?>/wp-admin/user-edit.php?user_id=${ambassador.user_id}">${ambassador.user_id}</a>
          </td>
          <td>
            <a href="<?php echo $site_url; ?>/wp-admin/admin.php?page=affiliate-wp-affiliates&affiliate_id=${ambassador.affiliate_id}&action=edit_affiliate">${ambassador.affiliate_id}</a>
          </td>
          <td>${ambassador.name}</td>
          <td>`
          if (ambassador.aff_site) {
            html += `<a href="${ambassador.aff_site}" title="View" target="_blank">${ambassador.aff_site}</a>`
          } else {
            html += `<a href="<?php echo $site_url; ?>/wp-admin/network/site-new.php" title="Create">Create Site</a>`
          }
          html += `
          </td>
        </tr>`
      jQuery('#ambassadors tbody').append(html)
      i++
    })
  }).fail( function(error) {
    console.error(error);
  });
}

jQuery(document).ready(function() {
	initPage()
})
</script>
