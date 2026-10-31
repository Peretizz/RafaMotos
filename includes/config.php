<?php
/**
 * Configurações da loja — edite aqui os dados que aparecem no site.
 * (A conexão com o banco continua em src/ConexaoBD.php, sem mudanças.)
 */

const LOJA_NOME       = 'Rafa Motos';
const LOJA_SLOGAN     = 'Mecânica · Peças · Vendas';
const LOJA_WHATSAPP   = '554998112215';          // só números, com 55 + DDD
const LOJA_TELEFONE   = '(49) 99811-2215';
const LOJA_TELEFONE_FIXO = '(49) 3565-5098';       // vem do banner da loja; deixe '' para não mostrar
const LOJA_EMAIL      = 'nickperetivendas@gmail.com';
const LOJA_ENDERECO   = 'R. Bulcão Viana, 695 - Videira - SC';
const LOJA_ANOS       = 2;                       // "há X anos no mercado"
const LOJA_INSTAGRAM  = '';                      // ex.: 'https://instagram.com/rafamotos' (vazio = não mostra)

// Simulador de parcelas (aparece nos detalhes de cada moto).
// A taxa é só uma estimativa — ajuste para a realidade dos bancos com que você trabalha.
const SIMULADOR_ATIVO = true;
const SIMULADOR_TAXA_MENSAL = 0.0249;            // 2,49% ao mês
const SIMULADOR_PRAZOS = [12, 24, 36, 48];

// Fotos
const FOTO_MAX_LADO   = 1600;   // lado maior, em pixels, ao enviar uma foto
const FOTO_QUALIDADE  = 82;     // qualidade JPEG (1–100)
const FOTO_MAX_POR_MOTO = 12;
const FOTO_MAX_MB     = 12;     // tamanho máximo de cada foto enviada

date_default_timezone_set('America/Sao_Paulo');
