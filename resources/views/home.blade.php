<x-layout>
	<div class="container">
		<div class="row justify-content-md-center my-2">
			<div class="col-md-4 text-center">
				<h1>Product List</h1>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class="col-md-4 border p-4">
				<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
				<div class="form-group row mb-2">
					<div class="col-sm-12 text-center">
						<input type="text" id="name" name="name" placeholder="Product Name" class="form-control" required />
					</div>
				</div>
				<div class="form-group row mb-2">
					<div class="col-sm-6">
						<input type="number" id="qty" name="qty" placeholder="Qty" class="form-control" required />
					</div>
					<div class="col-sm-6">
						<input type="number" id="price" name="price" placeholder="Price" class="form-control" required />
					</div>
				</div>
				<div class="form-group row mb-2">
					<div class="col-sm-12 text-center">
						<span id="message"></span>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<button onclick="createProduct()" class="form-control btn btn-success">Submit</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center mt-4">
			<table class="table col-md-9 text-center">
				<thead>
	                <tr>
						<td>Name</td>
						<td>Qty</td>
						<td>Price</td>
						<td>Date</td>
						<td>Total Value</td>
						<td>Actions</td>
					</tr>
	            </thead>
	            <tbody id="productTableBody">
	                <!-- Rows will be dynamically added by Ajax -->
	            </tbody>
			</table>
		</div>
	</div>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">View Product</h5>
	      </div>
	      <div class="modal-body">
        	<input type="hidden" name="_token" id="editToken" value="{{ csrf_token() }}">
			<div class="form-group row mb-2">
				<div class="col-sm-12 text-center">
					<input type="text" id="productId" class="d-none" name="productId" placeholder="Product ID" class="form-control" required />
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-sm-12 text-center">
					<input type="text" id="editProductName" name="editProductName" placeholder="Product Name" class="form-control" required />
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-sm-6">
					<input type="number" id="editProductQty" name="editProductQty" placeholder="Qty" class="form-control" required />
				</div>
				<div class="col-sm-6">
					<input type="number" id="editProductPrice" name="editProductPrice" placeholder="Price" class="form-control" required />
				</div>
			</div>
			<div class="form-group row mt-2">
				<div class="col-sm-12 text-center">
					<span id="modalMessage"></span>
				</div>
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button onclick="deleteProduct()" type="button" class="btn btn-danger">Delete</button>
	        <button onclick="editProduct()" type="button" class="btn btn-info">Update</button>
	      </div>
	    </div>
	  </div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			refreshProducts();
	    });

	    function refreshProducts()
	    {
	    	const productTableBody = document.getElementById('productTableBody');
			productTableBody.innerHTML = "";

	    	$.ajax({
				method:'GET',
				url: '/products',
                dataType : 'JSON',
				success: function(result) {
					$('#productTableBody').innerHTML = "";
		            jQuery.each(result, function(index, product) {
		                const row = document.createElement("tr");
		                row.innerHTML = `
		                    <td id="name-${product.id}">${product.name}</td>
		                    <td id="qty-${product.id}">${product.qty}</td>
		                    <td id="price-${product.id}" class="d-none">${product.price}</td>
		                    <td>${formatNumber(product.price)}</td>
		                    <td>${product.created_at}</td>
		                    <td>${formatNumber(product.qty * product.price)}</td>
		                    <td>
		                    	<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal" onclick="prepareProduct('${product.id}')">View</button>
		                    </td>
		                `;
		                productTableBody.appendChild(row);
		            });
				}
			});
	    }

	    function formatNumber(amount)
	    {
	    	return Intl.NumberFormat('en-US', {
				style: 'currency',
				currency: 'USD'
			}).format(amount);
	    }

		function createProduct()
		{
			const csrfToken = $('#token').val();
			const productName = $('#name').val();
			const productQty = $('#qty').val();
			const productPrice = $('#price').val();

			$.ajax({
				method:'POST',
				url: '/products',
				data: {
					_token: csrfToken,
					name: productName,
					qty: productQty,
					price: productPrice
				},
                dataType : 'JSON',
				success: function(result) {
					$('#message').html('<span class="text-success">' + result.message + '</span>');
					refreshProducts();
					$('#name').val('');
					$('#qty').val('');
					$('#price').val('');
				},
				error: function (request, status, error) {
					const result = jQuery.parseJSON( request.responseText );
			        $('#message').html('<span class="text-danger">' + result.message + '</span>');
			    }
			});
		}

		function prepareProduct(productId)
		{
			$('#productId').val(productId);

			const productName = $('#name-' + productId).html();
			$('#editProductName').val(productName);

			const productQty = $('#qty-' + productId).html();
			$('#editProductQty').val(productQty);

			const productPrice = $('#price-' + productId).html();
			$('#editProductPrice').val(productPrice);

			$('#modalMessage').html('');
		}

		function editProduct()
		{
			const csrfToken = $('#editToken').val();
			const productId = $('#productId').val();
			const productName = $('#editProductName').val();
			const productQty = $('#editProductQty').val();
			const productPrice = $('#editProductPrice').val();

			$.ajax({
				method:'PUT',
				url: '/products',
				data: {
					_token: csrfToken,
					productId: productId,
					editProductName: productName,
					editProductQty: productQty,
					editProductPrice: productPrice
				},
                dataType : 'JSON',
				success: function(result) {
					if (result.status == 'success') {
						$('#myModal').modal('hide');
						refreshProducts();
					}
					else {
						$('#modalMessage').html('<span class="text-danger">' + result.message + '</span>');
					}
				},
				error: function (request, status, error) {
					const result = jQuery.parseJSON( request.responseText );
			        $('#modalMessage').html('<span class="text-danger">' + result.message + '</span>');
			    }
			});
		}

		function deleteProduct()
		{
			const csrfToken = $('#editToken').val();
			const productId = $('#productId').val();

			$.ajax({
				method:'DELETE',
				url: '/products',
				data: {
					_token: csrfToken,
					productId: productId
				},
                dataType : 'JSON',
				success: function(result) {
					if (result.status == 'success') {
						$('#myModal').modal('hide');
						refreshProducts();
					}
					else {
						$('#modalMessage').html('<span class="text-danger">' + result.message + '</span>');
					}
				},
				error: function (request, status, error) {
					const result = jQuery.parseJSON( request.responseText );
			        $('#modalMessage').html('<span class="text-danger">' + result.message + '</span>');
			    }
			});
		}
	</script>
</x-layout>
