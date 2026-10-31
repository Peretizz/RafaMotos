<?php
/** Campos em comum do cadastro e da edição. Espera $moto (array|null) e $fotos (lista atual, na edição). */
$v = function ($k) use ($moto) { return e($moto[$k] ?? ''); };
$precoVal = isset($moto['preco']) ? number_format((float)$moto['preco'], 2, ',', '.') : '';
?>
<div class="campo">
    <label for="titulo">Título do anúncio</label>
    <input type="text" id="titulo" name="titulo" value="<?= $v('titulo') ?>" placeholder="Marca, modelo e ano. Ex.: Honda CB 300F 2024" maxlength="150" required>
    <small>O ano precisa ser a última parte do título. É assim que o site mostra o selo do ano e o filtro de marca.</small>
</div>
<div class="campo-par">
    <div class="campo">
        <label for="preco">Preço (R$)</label>
        <input type="text" id="preco" name="preco" value="<?= e($precoVal) ?>" inputmode="decimal" placeholder="Ex.: 19.900,00" required>
    </div>
    <div class="campo">
        <label for="km">Quilometragem</label>
        <input type="text" id="km" name="km" value="<?= $v('km') ?>" placeholder="Ex.: 30.000 km" required>
    </div>
</div>
<div class="campo-par">
    <div class="campo">
        <label for="cilindrada">Cilindrada</label>
        <input type="text" id="cilindrada" name="cilindrada" value="<?= $v('cilindrada') ?>" placeholder="Ex.: 160 cc">
    </div>
    <div class="campo">
        <label for="potencia">Potência</label>
        <input type="text" id="potencia" name="potencia" value="<?= $v('potencia') ?>" placeholder="Ex.: 14,3 cv">
    </div>
</div>
<div class="campo">
    <label for="transmissao">Transmissão</label>
    <input type="text" id="transmissao" name="transmissao" value="<?= $v('transmissao') ?>" placeholder="Ex.: 5 marchas">
</div>
