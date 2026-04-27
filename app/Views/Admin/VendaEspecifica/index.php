<?= $this->extend('Admin/layout/principal') ?>

<?= $this->section('titulo') ?>
Venda Específica
<?= $this->endSection() ?>

<?= $this->section('conteudos') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h4 style="color: #0055ff; margin-bottom: 20px;">
                <i class="fas fa-plus-circle"></i> Venda Específica
            </h4>
            <p class="text-muted">Crie vendas para clientes externos (vendas realizadas fora do site)</p>
        </div>
    </div>

    <div class="row">
        <!-- Seção Cliente -->
        <div class="col-lg-4 mb-4">
            <div class="card" style="background: #2d2d2d; border: 1px solid #333;">
                <div class="card-header" style="background: #0055ff; color: #000;">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Cliente</h5>
                </div>
                <div class="card-body">
                    <!-- Abas: Existing / New -->
                    <ul class="nav nav-tabs mb-3" id="clienteTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="existing-tab" data-bs-toggle="tab" data-bs-target="#existing" type="button" role="tab">
                                <i class="fas fa-list"></i> Selecionar Cliente
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="new-tab" data-bs-toggle="tab" data-bs-target="#new" type="button" role="tab">
                                <i class="fas fa-plus"></i> Novo Cliente
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="clienteTabContent">
                        <!-- Selecionar Cliente Existente -->
                        <div class="tab-pane fade show active" id="existing" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">Selecione um cliente</label>
                                <select class="form-select bg-dark text-light" id="cliente-select">
                                    <option value="">-- Selecione um cliente --</option>
                                </select>
                            </div>
                            <div id="cliente-selecionado" class="alert alert-success d-none">
                                <strong><i class="fas fa-check-circle"></i> Cliente selecionado:</strong>
                                <p class="mb-1" id="cliente-nome"></p>
                                <small class="text-muted" id="cliente-info"></small>
                                <button type="button" class="btn btn-sm btn-outline-danger float-end" onclick="limparCliente()">
                                    <i class="fas fa-times"></i> Alterar
                                </button>
                            </div>
                        </div>

                        <!-- Criar Novo Cliente -->
                        <div class="tab-pane fade" id="new" role="tabpanel">
                            <div class="mb-2">
                                <label class="form-label">Nome *</label>
                                <input type="text" class="form-control bg-dark text-light" id="novo-cliente-nome" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control bg-dark text-light" id="novo-cliente-email" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Telefone</label>
                                <input type="text" class="form-control bg-dark text-light" id="novo-cliente-telefone" placeholder="(00) 00000-0000">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Endereço</label>
                                <input type="text" class="form-control bg-dark text-light" id="novo-cliente-endereco" placeholder="Rua, número">
                            </div>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Número</label>
                                    <input type="number" class="form-control bg-dark text-light" id="novo-cliente-numero">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Complemento</label>
                                    <input type="text" class="form-control bg-dark text-light" id="novo-cliente-complemento">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" class="form-control bg-dark text-light" id="novo-cliente-bairro">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Cidade</label>
                                    <input type="text" class="form-control bg-dark text-light" id="novo-cliente-cidade">
                                </div>
                            </div>
                            <button type="button" class="btn btn-warning w-100" onclick="criarNovoCliente()">
                                <i class="fas fa-save"></i> Cadastrar Cliente
                            </button>
                            <div id="novo-cliente-result" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tipo de Entrega e Pagamento -->
            <div class="card mt-3" style="background: #2d2d2d; border: 1px solid #333;">
                <div class="card-body">
                    <h6 style="color: #0055ff; margin-bottom: 15px;">Tipo de Entrega</h6>
                    <div class="btn-group w-100 mb-3" role="group">
                        <input type="radio" class="btn-check" name="tipo_entrega" id="entrega" value="entrega" checked>
                        <label class="btn btn-outline-warning" for="entrega">
                            <i class="fas fa-motorcycle"></i> Entrega
                        </label>
                        <input type="radio" class="btn-check" name="tipo_entrega" id="retirada" value="retirada">
                        <label class="btn btn-outline-warning" for="retirada">
                            <i class="fas fa-shopping-bag"></i> Retirada
                        </label>
                    </div>

                    <div id="endereco-entrega">
                        <div class="mb-2">
                            <label class="form-label">Endereço</label>
                            <input type="text" class="form-control bg-dark text-light" id="endereco" placeholder="Rua, número, complemento">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Bairro</label>
                            <select class="form-select bg-dark text-light" id="bairro">
                                <option value="">-- Selecione --</option>
                            </select>
                        </div>
                        <div id="aviso-cobertura" class="alert alert-warning d-none">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Atenção:</strong> Este bairro não está na área de cobertura ou não tem taxa de entrega cadastrada.
                        </div>
                    </div>

                    <h6 style="color: #0055ff; margin-bottom: 15px; margin-top: 15px;">Forma de Pagamento</h6>
                    <select class="form-select bg-dark text-light mb-3" id="forma_pagamento">
                        <option value="dinheiro">Dinheiro</option>
                        <option value="pix">PIX</option>
                        <option value="cartao_credito">Cartão de Crédito</option>
                        <option value="cartao_debito">Cartão de Débito</option>
                    </select>

                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control bg-dark text-light" id="observacoes" rows="2" placeholder="Observações da venda..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção Produtos -->
        <div class="col-lg-8">
            <div class="card" style="background: #2d2d2d; border: 1px solid #333;">
                <div class="card-header" style="background: #0055ff; color: #000;">
                    <h5 class="mb-0"><i class="fas fa-hamburger"></i> Produtos</h5>
                </div>
                <div class="card-body">
                    <!-- Adicionar Produto -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Selecionar Produto</label>
                            <select class="form-select bg-dark text-light" id="produto-select">
                                <option value="">-- Selecione --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quantidade</label>
                            <input type="number" class="form-control bg-dark text-light" id="produto-qtd" value="1" min="1">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-warning w-100" onclick="adicionarProduto()">
                                <i class="fas fa-plus"></i> Adicionar
                            </button>
                        </div>
                    </div>

                    <!-- Extras do Produto -->
                    <div id="extras-container" style="display: none;" class="mb-3">
                        <div class="card bg-dark border-warning">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <strong>Extras Disponíveis</strong>
                                <span id="extras-info"></span>
                            </div>
                            <div class="card-body" id="extras-list"></div>
                        </div>
                    </div>

                    <!-- Lista de Itens -->
                    <div class="table-responsive">
                        <table class="table table-dark table-striped" id="tabela-itens">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Qtd</th>
                                    <th>Preço</th>
                                    <th>Total</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody id="lista-itens">
                                <tr id="sem-itens">
                                    <td colspan="5" class="text-center text-muted">Nenhum produto adicionado</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Subtotal</th>
                                    <th id="subtotal">R$ 0,00</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Taxa de Entrega</th>
                                    <th id="taxa-entrega">R$ 0,00</th>
                                    <th></th>
                                </tr>
                                <tr style="background: #0055ff; color: #000;">
                                    <th colspan="3">Total</th>
                                    <th id="valor-total">R$ 0,00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn btn-secondary" onclick="limparFormulario()">
                            <i class="fas fa-trash"></i> Limpar Tudo
                        </button>
                        <button type="button" class="btn btn-warning flex-grow-1" onclick="finalizarVenda()" id="btn-finalizar">
                            <i class="fas fa-check"></i> Finalizar Venda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let clienteSelecionado = null;
let itensVenda = [];
let taxaEntrega = 0;
let extrasProdutoAtual = [];
let configExtrasProduto = { obrigatorio_extras: 0, max_extras: 0 };
let bairrosData = [];
let clientesData = [];

document.addEventListener('DOMContentLoaded', function() {
    carregarProdutos();
    carregarClientes();
    carregarBairros();
    
    document.getElementById('bairro').addEventListener('change', buscarTaxaEntrega);
    document.getElementById('cliente-select').addEventListener('change', function() {
        const clienteId = this.value;
        if (clienteId) {
            const cliente = clientesData.find(c => c.id == clienteId);
            if (cliente) {
                selecionarCliente(cliente);
            }
        }
    });
    
    document.querySelectorAll('input[name="tipo_entrega"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const tipo = document.querySelector('input[name="tipo_entrega"]:checked').value;
            if (tipo === 'retirada') {
                document.getElementById('endereco-entrega').style.display = 'none';
                taxaEntrega = 0;
                atualizarTotais();
            } else {
                document.getElementById('endereco-entrega').style.display = 'block';
                buscarTaxaEntrega();
            }
        });
    });
    
    document.getElementById('produto-select').addEventListener('change', function() {
        const produtoId = this.value;
        if (produtoId) {
            carregarExtrasProduto(produtoId);
        } else {
            document.getElementById('extras-container').style.display = 'none';
        }
    });
});

function carregarClientes() {
    fetch('<?= site_url('admin/venda-especifica/todos-clientes') ?>')
        .then(r => r.json())
        .then(data => {
            clientesData = data.data || [];
            const select = document.getElementById('cliente-select');
            select.innerHTML = '<option value="">-- Selecione um cliente --</option>';
            
            clientesData.forEach(cliente => {
                select.innerHTML += `<option value="${cliente.id}" 
                    data-nome="${cliente.nome}" 
                    data-telefone="${cliente.telefone || ''}" 
                    data-endereco="${cliente.endereco || ''}" 
                    data-bairro="${cliente.bairro || ''}"
                    data-cidade="${cliente.cidade || ''}"
                    data-numero="${cliente.numero || ''}"
                    data-complemento="${cliente.complemento || ''}">
                    ${cliente.nome} - ${cliente.telefone || 'Sem telefone'}
                </option>`;
            });
        })
        .catch(err => console.error('Erro ao carregar clientes:', err));
}

function carregarBairros() {
    fetch('<?= site_url('admin/venda-especifica/bairros') ?>')
        .then(r => r.json())
        .then(data => {
            bairrosData = data.data || [];
            const select = document.getElementById('bairro');
            select.innerHTML = '<option value="">-- Selecione --</option>';
            
            if (bairrosData.length > 0) {
                bairrosData.forEach(bairro => {
                    const taxaFormatada = bairro.taxa_entrega > 0 ? 'R$ ' + parseFloat(bairro.taxa_entrega).toFixed(2).replace('.', ',') : 'Grátis';
                    const inativoLabel = bairro.ativo == 0 ? ' (inativo)' : '';
                    select.innerHTML += `<option value="${bairro.nome}" data-taxa="${bairro.taxa_entrega || 0}" data-ativo="${bairro.ativo}">${bairro.nome} - ${taxaFormatada}${inativoLabel}</option>`;
                });
            } else {
                console.warn('Nenhum bairro cadastrado');
            }
        })
        .catch(err => {
            console.error('Erro ao carregar bairros:', err);
            const select = document.getElementById('bairro');
            select.innerHTML = '<option value="">-- Erro ao carregar bairros --</option>';
        });
}

function selecionarCliente(cliente) {
    if (typeof cliente === 'object') {
        clienteSelecionado = {
            id: cliente.id,
            nome: cliente.nome,
            telefone: cliente.telefone || '',
            endereco: cliente.endereco || '',
            bairro: cliente.bairro || '',
            cidade: cliente.cidade || '',
            numero: cliente.numero || '',
            complemento: cliente.complemento || ''
        };
    } else {
        return;
    }
    
    document.getElementById('cliente-select').value = clienteSelecionado.id;
    document.getElementById('cliente-selecionado').classList.remove('d-none');
    document.getElementById('cliente-nome').textContent = clienteSelecionado.nome;
    document.getElementById('cliente-info').textContent = (clienteSelecionado.telefone || 'Sem telefone') + ' - ' + (clienteSelecionado.bairro || '');
    
    // Preencher endereço completo
    let enderecoCompleto = clienteSelecionado.endereco || '';
    if (clienteSelecionado.numero) {
        enderecoCompleto += (enderecoCompleto ? ', ' : '') + clienteSelecionado.numero;
    }
    if (clienteSelecionado.complemento) {
        enderecoCompleto += (enderecoCompleto ? ' - ' : '') + clienteSelecionado.complemento;
    }
    document.getElementById('endereco').value = enderecoCompleto;
    
    // Preencher bairro e calcular taxa
    if (clienteSelecionado.bairro) {
        const selectBairro = document.getElementById('bairro');
        
        // Normalizar string para comparação
        const normalizarString = (str) => {
            return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
        };
        
        const bairroClienteNormalizado = normalizarString(clienteSelecionado.bairro);
        
        // Buscar bairro correspondente na lista
        const bairroOption = Array.from(selectBairro.options).find(opt => 
            normalizarString(opt.value) === bairroClienteNormalizado
        );
        
        if (bairroOption) {
            selectBairro.value = bairroOption.value;
        } else {
            // Se o bairro não existe na lista, adiciona como opção temporária
            const newOption = document.createElement('option');
            newOption.value = clienteSelecionado.bairro;
            newOption.textContent = clienteSelecionado.bairro + ' (do cadastro do cliente)';
            newOption.dataset.taxa = '0';
            newOption.dataset.ativo = '0';
            selectBairro.appendChild(newOption);
            selectBairro.value = clienteSelecionado.bairro;
        }
        
        buscarTaxaEntrega();
    }
}

function limparCliente() {
    clienteSelecionado = null;
    document.getElementById('cliente-select').value = '';
    document.getElementById('cliente-selecionado').classList.add('d-none');
    document.getElementById('endereco').value = '';
    document.getElementById('bairro').value = '';
    document.getElementById('aviso-cobertura').classList.add('d-none');
    taxaEntrega = 0;
    atualizarTotais();
}

function criarNovoCliente() {
    const nome = document.getElementById('novo-cliente-nome').value.trim();
    const email = document.getElementById('novo-cliente-email').value.trim();
    const telefone = document.getElementById('novo-cliente-telefone').value.trim();
    const endereco = document.getElementById('novo-cliente-endereco').value.trim();
    const numero = document.getElementById('novo-cliente-numero').value.trim();
    const complemento = document.getElementById('novo-cliente-complemento').value.trim();
    const bairro = document.getElementById('novo-cliente-bairro').value.trim();
    const cidade = document.getElementById('novo-cliente-cidade').value.trim();

    const resultDiv = document.getElementById('novo-cliente-result');

    if (!nome) {
        resultDiv.innerHTML = '<div class="alert alert-danger">Nome é obrigatório</div>';
        return;
    }
    if (!email) {
        resultDiv.innerHTML = '<div class="alert alert-danger">Email é obrigatório</div>';
        return;
    }

    const dados = {
        nome: nome,
        email: email,
        telefone: telefone,
        endereco: endereco,
        numero: numero,
        complemento: complemento,
        bairro: bairro,
        cidade: cidade
    };

    fetch('<?= site_url('admin/venda-especifica/criar-cliente') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dados)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
            
            clienteSelecionado = {
                id: data.cliente.id,
                nome: data.cliente.nome,
                telefone: data.cliente.telefone,
                endereco: data.cliente.endereco,
                bairro: data.cliente.bairro
            };
            
            document.getElementById('cliente-selecionado').classList.remove('d-none');
            document.getElementById('cliente-nome').textContent = data.cliente.nome;
            document.getElementById('cliente-info').textContent = (data.cliente.telefone || 'Sem telefone') + ' - ' + (data.cliente.bairro || '');
            
            document.getElementById('endereco').value = data.cliente.endereco || '';
            document.getElementById('bairro').value = data.cliente.bairro || '';
            
            carregarClientes();
            
            setTimeout(() => {
                const tab = new bootstrap.Tab(document.getElementById('existing-tab'));
                tab.show();
            }, 1500);
            
            buscarTaxaEntrega();
        } else {
            resultDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
        }
    })
    .catch(err => {
        resultDiv.innerHTML = '<div class="alert alert-danger">Erro ao criar cliente</div>';
    });
}

function carregarProdutos() {
    fetch('<?= site_url('admin/venda-especifica/produtos') ?>')
        .then(r => r.json())
        .then(data => {
            const select = document.getElementById('produto-select');
            select.innerHTML = '<option value="">-- Selecione --</option>';
            
            console.log('Produtos carregados:', data);
            
            if (data.data && data.data.length > 0) {
                data.data.forEach(produto => {
                    const preco = parseFloat(produto.preco).toFixed(2).replace('.', ',');
                    select.innerHTML += `<option value="${produto.id}" data-nome="${produto.nome}" data-preco="${produto.preco}">${produto.nome} - R$ ${preco}</option>`;
                });
            } else {
                console.log('Nenhum produto encontrado');
            }
        })
        .catch(err => console.error('Erro ao carregar produtos:', err));
}

function carregarExtrasProduto(produtoId) {
    fetch('<?= site_url('admin/venda-especifica/produto-extras') ?>/' + produtoId)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.extras && data.extras.length > 0) {
                extrasProdutoAtual = data.extras;
                configExtrasProduto = {
                    obrigatorio_extras: data.obrigatorio_extras,
                    max_extras: data.max_extras
                };
                renderizarExtras();
                document.getElementById('extras-container').style.display = 'block';
            } else {
                extrasProdutoAtual = [];
                document.getElementById('extras-container').style.display = 'none';
            }
        })
        .catch(err => {
            console.error('Erro ao carregar extras:', err);
            document.getElementById('extras-container').style.display = 'none';
        });
}

function renderizarExtras() {
    let html = '';
    let info = '';
    
    if (configExtrasProduto.obrigatorio_extras > 0) {
        info += `<span class="badge bg-danger">${configExtrasProduto.obrigatorio_extras} obrigatório(s)</span> `;
    }
    if (configExtrasProduto.max_extras > 0) {
        info += `<span class="badge bg-info">Máx: ${configExtrasProduto.max_extras}</span>`;
    }
    document.getElementById('extras-info').innerHTML = info;
    
    extrasProdutoAtual.forEach(extra => {
        const preco = extra.preco.toFixed(2).replace('.', ',');
        html += `
            <div class="mb-2 p-2 border border-secondary rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="text-light">${extra.nome}</strong>
                        ${extra.descricao ? `<br><small class="text-muted">${extra.descricao}</small>` : ''}
                        <br><span class="text-warning">+ R$ ${preco}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        ${extra.multitude ? `
                            <button type="button" class="btn btn-sm btn-secondary" onclick="alterarQtdExtra(${extra.id}, -1)">-</button>
                            <input type="number" class="form-control form-control-sm bg-dark text-light text-center" 
                                   id="extra-qtd-${extra.id}" value="0" min="0" style="width: 60px;" 
                                   onchange="validarQtdExtra(${extra.id})">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="alterarQtdExtra(${extra.id}, 1)">+</button>
                        ` : `
                            <input type="checkbox" class="form-check-input" id="extra-check-${extra.id}" 
                                   onchange="toggleExtra(${extra.id})">
                        `}
                    </div>
                </div>
            </div>
        `;
    });
    
    document.getElementById('extras-list').innerHTML = html;
}

function alterarQtdExtra(extraId, delta) {
    const input = document.getElementById(`extra-qtd-${extraId}`);
    let qtd = parseInt(input.value) || 0;
    qtd = Math.max(0, qtd + delta);
    input.value = qtd;
    validarQtdExtra(extraId);
}

function toggleExtra(extraId) {
    const checkbox = document.getElementById(`extra-check-${extraId}`);
    const totalSelecionados = extrasProdutoAtual.filter(e => {
        const check = document.getElementById(`extra-check-${e.id}`);
        return check && check.checked;
    }).length;
    
    if (configExtrasProduto.max_extras > 0 && totalSelecionados > configExtrasProduto.max_extras) {
        checkbox.checked = false;
        alert(`Você pode selecionar no máximo ${configExtrasProduto.max_extras} extra(s)`);
    }
}

function validarQtdExtra(extraId) {
    const input = document.getElementById(`extra-qtd-${extraId}`);
    let qtd = parseInt(input.value) || 0;
    
    const totalExtras = extrasProdutoAtual.reduce((sum, e) => {
        const inp = document.getElementById(`extra-qtd-${e.id}`);
        return sum + (inp ? parseInt(inp.value) || 0 : 0);
    }, 0);
    
    if (configExtrasProduto.max_extras > 0 && totalExtras > configExtrasProduto.max_extras) {
        input.value = Math.max(0, qtd - (totalExtras - configExtrasProduto.max_extras));
        alert(`Você pode selecionar no máximo ${configExtrasProduto.max_extras} extra(s)`);
    }
}

function buscarTaxaEntrega() {
    const selectBairro = document.getElementById('bairro');
    const bairro = selectBairro.value;
    const tipoEntrega = document.querySelector('input[name="tipo_entrega"]:checked').value;
    
    const avisoCobertura = document.getElementById('aviso-cobertura');
    
    if (tipoEntrega === 'retirada') {
        taxaEntrega = 0;
        avisoCobertura.classList.add('d-none');
        atualizarTotais();
        return;
    }
    
    if (!bairro) {
        taxaEntrega = 0;
        avisoCobertura.classList.add('d-none');
        atualizarTotais();
        return;
    }

    // Normalizar string para comparação (remove acentos e converte para minúsculas)
    const normalizarString = (str) => {
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
    };

    const bairroNormalizado = normalizarString(bairro);
    
    // Buscar bairro na lista carregada
    const bairroEncontrado = bairrosData.find(b => 
        normalizarString(b.nome) === bairroNormalizado
    );
    
    // Verificar se o bairro existe, está ativo e tem taxa de entrega válida
    if (bairroEncontrado && bairroEncontrado.ativo == 1 && parseFloat(bairroEncontrado.taxa_entrega) >= 0) {
        taxaEntrega = parseFloat(bairroEncontrado.taxa_entrega);
        avisoCobertura.classList.add('d-none');
    } else {
        taxaEntrega = 0;
        avisoCobertura.classList.remove('d-none');
    }
    
    atualizarTotais();
}

function adicionarProduto() {
    const select = document.getElementById('produto-select');
    const qtd = parseInt(document.getElementById('produto-qtd').value) || 1;
    
    if (!select.value) {
        alert('Selecione um produto');
        return;
    }

    const nome = select.options[select.selectedIndex].dataset.nome;
    const preco = parseFloat(select.options[select.selectedIndex].dataset.preco);

    const extras = [];
    extrasProdutoAtual.forEach(extra => {
        if (extra.multitude) {
            const qtdExtra = parseInt(document.getElementById(`extra-qtd-${extra.id}`).value) || 0;
            if (qtdExtra > 0) {
                extras.push({ id: extra.id, nome: extra.nome, preco: extra.preco, quantidade: qtdExtra });
            }
        } else {
            const checked = document.getElementById(`extra-check-${extra.id}`).checked;
            if (checked) {
                extras.push({ id: extra.id, nome: extra.nome, preco: extra.preco, quantidade: 1 });
            }
        }
    });

    if (configExtrasProduto.obrigatorio_extras > 0 && extras.length < configExtrasProduto.obrigatorio_extras) {
        alert(`Selecione pelo menos ${configExtrasProduto.obrigatorio_extras} extra(s)`);
        return;
    }

    const precoExtras = extras.reduce((sum, e) => sum + (e.preco * e.quantidade), 0);
    const precoTotal = (preco + precoExtras) * qtd;

    itensVenda.push({
        id: select.value,
        nome: nome,
        preco: preco,
        quantidade: qtd,
        extras: extras,
        total: precoTotal,
        observacoes: ''
    });

    document.getElementById('produto-qtd').value = 1;
    select.value = '';
    document.getElementById('extras-container').style.display = 'none';
    extrasProdutoAtual = [];
    atualizarTabela();
}

function removerProduto(index) {
    itensVenda.splice(index, 1);
    atualizarTabela();
}

function atualizarTabela() {
    const tbody = document.getElementById('lista-itens');
    
    if (itensVenda.length === 0) {
        tbody.innerHTML = '<tr id="sem-itens"><td colspan="5" class="text-center text-muted">Nenhum produto adicionado</td></tr>';
    } else {
        tbody.innerHTML = '';
        itensVenda.forEach((item, index) => {
            let extrasHtml = '';
            if (item.extras && item.extras.length > 0) {
                extrasHtml = '<br><small class="text-muted">+ ' + 
                    item.extras.map(e => `${e.nome} (${e.quantidade}x)`).join(', ') + 
                    '</small>';
            }
            tbody.innerHTML += `
                <tr>
                    <td>${item.nome}${extrasHtml}</td>
                    <td>${item.quantidade}</td>
                    <td>R$ ${item.preco.toFixed(2).replace('.', ',')}</td>
                    <td>R$ ${item.total.toFixed(2).replace('.', ',')}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="removerProduto(${index})"><i class="fas fa-times"></i></button></td>
                </tr>
            `;
        });
    }

    atualizarTotais();
}

function atualizarTotais() {
    const subtotal = itensVenda.reduce((sum, item) => sum + item.total, 0);
    const total = subtotal + taxaEntrega;

    document.getElementById('subtotal').textContent = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
    document.getElementById('taxa-entrega').textContent = 'R$ ' + taxaEntrega.toFixed(2).replace('.', ',');
    document.getElementById('valor-total').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
}

function limparFormulario() {
    itensVenda = [];
    taxaEntrega = 0;
    extrasProdutoAtual = [];
    clienteSelecionado = null;
    document.getElementById('extras-container').style.display = 'none';
    document.getElementById('cliente-select').value = '';
    document.getElementById('cliente-selecionado').classList.add('d-none');
    document.getElementById('endereco').value = '';
    document.getElementById('bairro').value = '';
    document.getElementById('aviso-cobertura').classList.add('d-none');
    document.getElementById('novo-cliente-result').innerHTML = '';
    document.querySelectorAll('#novo-cliente-nome, #novo-cliente-email, #novo-cliente-telefone, #novo-cliente-endereco, #novo-cliente-numero, #novo-cliente-complemento, #novo-cliente-bairro, #novo-cliente-cidade').forEach(el => el.value = '');
    document.getElementById('forma_pagamento').value = 'dinheiro';
    document.getElementById('observacoes').value = '';
    atualizarTabela();
}

function finalizarVenda() {
    const nomeCliente = clienteSelecionado ? clienteSelecionado.nome : $('#novo-cliente-nome').val().trim();
    const telefone = clienteSelecionado ? clienteSelecionado.telefone : $('#novo-cliente-telefone').val().trim();
    const endereco = document.getElementById('endereco').value.trim();
    const bairro = document.getElementById('bairro').value.trim();
    const formaPagamento = document.getElementById('forma_pagamento').value;
    const observacoes = document.getElementById('observacoes').value.trim();
    const tipoEntrega = document.querySelector('input[name="tipo_entrega"]:checked').value;

    if (!nomeCliente || !telefone) {
        alert('Selecione ou cadastre um cliente');
        return;
    }

    if (tipoEntrega === 'entrega' && !bairro) {
        alert('Para entrega, selecione o bairro');
        return;
    }

    if (itensVenda.length === 0) {
        alert('Adicione pelo menos um produto');
        return;
    }

    const btn = document.getElementById('btn-finalizar');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';

    const dados = {
        nome_cliente: nomeCliente,
        telefone: telefone,
        endereco: endereco,
        bairro: bairro,
        forma_pagamento: formaPagamento,
        observacoes: observacoes,
        tipo_entrega: tipoEntrega,
        itens: itensVenda
    };

    fetch('<?= site_url('admin/venda-especifica/criar') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dados)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Venda criada com sucesso! Código: ' + data.codigo);
            window.location.href = '<?= site_url('admin/pedidos') ?>/' + data.pedido_id;
        } else {
            alert('Erro: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Finalizar Venda';
        }
    })
    .catch(err => {
        alert('Erro ao processar venda');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Finalizar Venda';
    });
}
</script>
<?= $this->endSection() ?>
