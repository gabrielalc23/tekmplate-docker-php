<?php
namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EnterpriseController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Cria uma nova empresa (POST /enterprises)
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validação básica
        if (empty($data['cnpj'])) {
            return new JsonResponse(['error' => 'CNPJ é obrigatório'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->connection->insert('empresas', [
                'nome' => $data['nome'] ?? null,
                'razao_social' => $data['razao_social'] ?? null,
                'endereco' => $data['endereco'] ?? null,
                'complemento' => $data['complemento'] ?? null,
                'cidade_estado' => $data['cidade_estado'] ?? null,
                'telefone_ddd' => $data['telefone_ddd'] ?? null,
                'email' => $data['email'] ?? null,
                'observacoes' => $data['observacoes'] ?? null,
                'data_fundacao' => $data['data_fundacao'] ?? null,
                'formato_juridico' => $data['formato_juridico'] ?? null,
                'cnae' => $data['cnae'] ?? null,
                'cnpj' => $data['cnpj'],
                'aliquota_imposto' => $data['aliquota_imposto'] ?? null,
                'ano_inicial_plano_negocios' => $data['ano_inicial_plano_negocios'] ?? null,
                'mes_inicial_plano_negocios' => $data['mes_inicial_plano_negocios'] ?? null,
                'quantidade_anos_plano_negocios' => $data['quantidade_anos_plano_negocios'] ?? null,
            ]);

            return new JsonResponse(['message' => 'Empresa criada com sucesso'], Response::HTTP_CREATED);
        } catch (\Doctrine\DBAL\Exception $e) {
            return new JsonResponse(
                ['error' => 'Erro ao criar empresa: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Recupera uma empresa por CNPJ (GET /enterprises/{cnpj})
     */
    public function show(string $cnpj): JsonResponse
    {
        try {
            $enterprise = $this->connection->fetchAssociative(
                'SELECT * FROM empresas WHERE cnpj = ?',
                [$cnpj]
            );

            if (!$enterprise) {
                return new JsonResponse(
                    ['error' => 'Empresa não encontrada'],
                    Response::HTTP_NOT_FOUND
                );
            }

            return new JsonResponse($enterprise);
        } catch (\Doctrine\DBAL\Exception $e) {
            return new JsonResponse(
                ['error' => 'Erro ao buscar empresa: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Atualiza uma empresa (PUT /enterprises/{cnpj})
     */
    public function update(Request $request, string $cnpj): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            // Verifica se a empresa existe
            $existing = $this->connection->fetchOne(
                'SELECT 1 FROM empresas WHERE cnpj = ?',
                [$cnpj]
            );

            if (!$existing) {
                return new JsonResponse(
                    ['error' => 'Empresa não encontrada'],
                    Response::HTTP_NOT_FOUND
                );
            }

            $this->connection->update('empresas', [
                'nome' => $data['nome'] ?? null,
                'razao_social' => $data['razao_social'] ?? null,
                'endereco' => $data['endereco'] ?? null,
                'complemento' => $data['complemento'] ?? null,
                'cidade_estado' => $data['cidade_estado'] ?? null,
                'telefone_ddd' => $data['telefone_ddd'] ?? null,
                'email' => $data['email'] ?? null,
                'observacoes' => $data['observacoes'] ?? null,
                'data_fundacao' => $data['data_fundacao'] ?? null,
                'formato_juridico' => $data['formato_juridico'] ?? null,
                'cnae' => $data['cnae'] ?? null,
                'aliquota_imposto' => $data['aliquota_imposto'] ?? null,
                'ano_inicial_plano_negocios' => $data['ano_inicial_plano_negocios'] ?? null,
                'mes_inicial_plano_negocios' => $data['mes_inicial_plano_negocios'] ?? null,
                'quantidade_anos_plano_negocios' => $data['quantidade_anos_plano_negocios'] ?? null,
            ], ['cnpj' => $cnpj]);

            return new JsonResponse(['message' => 'Empresa atualizada com sucesso']);
        } catch (\Doctrine\DBAL\Exception $e) {
            return new JsonResponse(
                ['error' => 'Erro ao atualizar empresa: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove uma empresa (DELETE /enterprises/{cnpj})
     */
    public function delete(string $cnpj): JsonResponse
    {
        try {
            $affectedRows = $this->connection->delete('empresas', ['cnpj' => $cnpj]);

            if ($affectedRows === 0) {
                return new JsonResponse(
                    ['error' => 'Empresa não encontrada'],
                    Response::HTTP_NOT_FOUND
                );
            }

            return new JsonResponse(['message' => 'Empresa removida com sucesso']);
        } catch (\Doctrine\DBAL\Exception $e) {
            return new JsonResponse(
                ['error' => 'Erro ao remover empresa: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Lista todas as empresas (GET /enterprises)
     */
    public function index(): \JsonResponse
    {
        try {
            $enterprises = $this->connection->fetchAllAssociative('SELECT * FROM empresas');
            return new \JsonResponse($enterprises);
        } catch (\Doctrine\DBAL\Exception $e) {
            return new \JsonResponse(
                ['error' => 'Erro ao listar empresas: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Busca empresas (GET /enterprises/search?q=termo)
     */
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('q', '');

        try {
            $queryBuilder = $this->connection->createQueryBuilder();
            $enterprises = $queryBuilder
                ->select('*')
                ->from('empresas')
                ->where('nome LIKE :search OR razao_social LIKE :search')
                ->setParameter('search', '%' . $searchTerm . '%')
                ->execute()
                ->fetchAllAssociative();

            return new JsonResponse($enterprises);
        } catch (\Doctrine\DBAL\Exception $e) {
            return new JsonResponse(
                ['error' => 'Erro ao buscar empresas: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}