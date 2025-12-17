// assets/js/wishlist.js
$(function(){
  // adiciona ao clique do botão com data-product-id
  $(document).on('click', '.btn-wishlist-add', function(e){
    e.preventDefault();
    const productId = $(this).data('product-id');
    const $btn = $(this);
    $.post('api/wishlist.php?action=add', { product_id: productId }, function(res){
      if(res.success){
        $btn.addClass('disabled').text('✔ Na lista');
      } else {
        if(res.msg === 'Não autenticado') {
          window.location.href = 'login.php';
        } else alert('Erro ao adicionar à lista');
      }
    }, 'json').fail(function(){ alert('Erro de requisição'); });
  });

  // remover da wishlist (botão na minha_conta)
  $(document).on('click', '.btn-wishlist-remove', function(e){
    e.preventDefault();
    const productId = $(this).data('product-id');
    const $row = $(this).closest('.wishlist-row');
    if(!confirm('Remover da lista de desejos?')) return;
    $.post('api/wishlist.php?action=remove', { product_id: productId }, function(res){
      if(res.success){
        $row.remove();
      } else alert('Erro ao remover');
    }, 'json').fail(function(){ alert('Erro de requisição'); });
  });
});
