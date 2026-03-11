import sys
import json
import base64
import io
from typing import Dict, Any, List
import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
from warnings import filterwarnings

# Ignorar avisos para um output mais limpo
filterwarnings('ignore')

def salvar_grafico_para_base64() -> str:
    """Salva a figura atual do Matplotlib em uma string base64 e fecha a figura."""
    buf = io.BytesIO()
    plt.savefig(buf, format='png', bbox_inches='tight')
    buf.seek(0)
    imagem_base64 = base64.b64encode(buf.read()).decode('utf-8')
    plt.close() # Fecha a figura para liberar memória
    return f"data:image/png;base64,{imagem_base64}"

def criar_grafico_distribuicao_idade_gestacional(df: pd.DataFrame) -> str:
    """Cria um histograma da idade gestacional."""
    plt.figure(figsize=(10, 6))
    sns.histplot(data=df, x='idade_gestacional', kde=True, bins=25, color='skyblue')
    plt.title('Distribuição da Idade Gestacional (Semanas)', fontsize=14)
    plt.xlabel('Idade Gestacional')
    plt.ylabel('Número de Consultas')
    return salvar_grafico_para_base64()

def criar_grafico_casos_chd(df: pd.DataFrame) -> str:
    """Cria um gráfico de pizza para casos de CHD confirmados."""
    plt.figure(figsize=(8, 8))
    
    # Garante que a coluna exista antes de prosseguir
    if 'chd_confirmada' not in df.columns:
        plt.text(0.5, 0.5, 'Dados de CHD não disponíveis', ha='center', va='center')
        plt.title('Proporção de Casos de CHD')
        plt.axis('equal')
        return salvar_grafico_para_base64()

    # Garante que a coluna seja tratada como booleano/int para contagem
    chd_counts = df['chd_confirmada'].astype(bool).value_counts()
    labels = {True: 'CHD Confirmado', False: 'Não Confirmado'}
    
    # Garante que temos ambos os valores para o gráfico
    if True not in chd_counts: chd_counts[True] = 0.0
    if False not in chd_counts: chd_counts[False] = 0.0

    plt.pie(chd_counts, labels=[labels[i] for i in chd_counts.index], colors=['lightcoral', 'lightskyblue'], autopct='%1.1f%%', startangle=140)
    plt.title('Proporção de Casos de CHD Confirmados')
    plt.axis('equal')
    return salvar_grafico_para_base64()

def main() -> None:
    try:
        # 1. LER DADOS DO LARAVEL (via argumento de linha de comando)
        if len(sys.argv) < 2:
            raise ValueError("Argumento JSON não fornecido.")
            
        dados_json = json.loads(sys.argv[1])
        consultas: List[Dict[str, Any]] = dados_json.get('historico_consultas', [])

        if not consultas:
            raise ValueError("A lista 'historico_consultas' está vazia ou não foi encontrada no JSON.")

        # Converte para um DataFrame do Pandas para fácil manipulação
        df = pd.DataFrame(consultas)

        # 2. GERAR OS GRÁFICOS PARA O DASHBOARD
        grafico1_b64 = criar_grafico_distribuicao_idade_gestacional(df)
        grafico2_b64 = criar_grafico_casos_chd(df)

        # 3. PREPARAR O RESULTADO PARA RETORNAR AO LARAVEL
        resultado = {
            "imagens": {
                "distribuicao_idade_gestacional": grafico1_b64,
                "proporcao_casos_chd": grafico2_b64
            }
        }
    except (json.JSONDecodeError, ValueError, KeyError) as e:
        resultado = {"error": f"Falha ao processar os dados: {str(e)}"}

    # 4. IMPRIMIR O RESULTADO COMO JSON PARA O PHP CAPTURAR
    print(json.dumps(resultado))

if __name__ == "__main__":
    main()
