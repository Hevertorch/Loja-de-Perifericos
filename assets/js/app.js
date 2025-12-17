// assets/js/app.js
$(function(){
  const modal = new bootstrap.Modal($('#modalProd')[0]);

  function showAlert(msg, type='success'){
    $('#alerta').html(`<div class="alert alert-${type}">${msg}</div>`);
    setTimeout(()=>$('#alerta').html(''), 3000);
  }

  function listar(){
    $.getJSON('api/produto.php?action=list', function(res){
      if(res.success){
        // montar tabela administrativa
        const rows = res.data.map(p => {
          let botoes = '';
          if (USER_TIPO === 'funcionario' || USER_TIPO === 'admin') {
            botoes = `
              <button class="btn btn-sm btn-info btn-edit" data-id="${p.id}">Editar</button>
              <button class="btn btn-sm btn-danger btn-del" data-id="${p.id}">Excluir</button>
            `;
          }
          return `
            <tr>
              <td>${p.id}</td>
              <td>${p.nome}</td>
              <td>${p.preco}</td>
              <td>${p.estoque}</td>
              <td>${p.categoria||''}</td>
              <td>${botoes}</td>
            </tr>
          `;
        }).join('');
        $('#tabela tbody').html(rows);

        // cards públicos (aqui mostramos sempre, pois painel já está protegido)
        const cards = res.data.map(p => `
          <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">${p.nome}</h5>
                <p class="card-text">${p.descricao ? (p.descricao.substring(0,100) + '...') : ''}</p>
                <p class="card-text"><strong>R$ ${p.preco}</strong></p>
                <p class="card-text"><small class="text-muted">${p.categoria||''}</small></p>
              </div>
            </div>
          </div>
        `).join('');
        $('#cards').html(cards);
      } else {
        showAlert('Erro ao listar produtos', 'danger');
      }
    });
  }

  listar();

  $('#btnNovo').click(function(){
    $('#formProd')[0].reset();
    $('#prod_id').val('');
    modal.show();
  });

  // submit (create/update)
  $('#formProd').submit(function(e){
    e.preventDefault();
    const id = $('#prod_id').val();
    const action = id ? 'update' : 'create';
    const data = $(this).serialize() + (id ? `&id=${id}` : '');
    $.post(`api/produto.php?action=${action}`, data, function(res){
      if(res.success){
        showAlert('Salvo com sucesso');
        modal.hide();
        listar();
      } else showAlert('Erro ao salvar', 'danger');
    }, 'json');
  });

  // editar
  $(document).on('click', '.btn-edit', function(){
    const id = $(this).data('id');
    $.getJSON(`api/produto.php?action=get&id=${id}`, function(res){
      if(res.success){
        const p = res.data;
        $('#prod_id').val(p.id);
        $('#prod_nome').val(p.nome);
        $('#prod_desc').val(p.descricao);
        $('#prod_preco').val(p.preco);
        $('#prod_estoque').val(p.estoque);
        $('#prod_categoria').val(p.categoria_id);
        modal.show();
      } else showAlert('Erro ao carregar produto', 'danger');
    });
  });

  // excluir
  $(document).on('click', '.btn-del', function(){
    if(!confirm('Confirma exclusão?')) return;
    const id = $(this).data('id');
    $.post('api/produto.php?action=delete', {id}, function(res){
      if(res.success){
        showAlert('Excluído');
        listar();
      } else showAlert('Erro ao excluir', 'danger');
    }, 'json');
  });
});
