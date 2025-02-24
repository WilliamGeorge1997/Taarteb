<?php

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

/**
 * Returns a JSON response with status, message, and data
 *
 * @param bool $status Whether the operation was successful
 * @param string|null $message Response message
 * @param mixed $data Response data
 * @param string $statusCode HTTP status code identifier
 * @return JsonResponse
 */
function returnMessage(bool $status = false, ?string $message = null, mixed $data = null, string $statusCode = 'ok'): JsonResponse
{
    return new JsonResponse(
        [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ],
        getStatusCode($statusCode)
    );
}

/**
 * Returns a JSON response with status, message, and errors
 *
 * @param bool $status Whether the operation was successful
 * @param string|null $message Response message
 * @param mixed $errors Response errors
 * @param string $statusCode HTTP status code identifier
 * @return JsonResponse
 */
function returnValidationMessage(bool $status = false, ?string $message = null, mixed $errors = null, string $statusCode = 'unprocessable_entity'): JsonResponse
{
    return new JsonResponse(
        [
            'status' => $status,
            'message' => $message,
            'errors' => $errors
        ],
        getStatusCode($statusCode)
    );
}
/**
 * Get HTTP status code from status type
 *
 * @param string $type Status code identifier
 * @return int HTTP status code
 */
function getStatusCode(string $type = 'ok'): int
{
    return allStatusCode()[strtolower($type)] ?? Response::HTTP_OK;
}

/**
 * Returns an array of common HTTP status codes with their descriptive keys
 *
 * @return array<string, int>
 */
function allStatusCode(): array
{
    return [
        "ok" => Response::HTTP_OK,
        "created" => Response::HTTP_CREATED,
        "accepted" => Response::HTTP_ACCEPTED,
        "no_content" => Response::HTTP_NO_CONTENT,
        "moved" => Response::HTTP_MOVED_PERMANENTLY,
        "found" => Response::HTTP_FOUND,
        "see_other" => Response::HTTP_SEE_OTHER,
        "not_modified" => Response::HTTP_NOT_MODIFIED,
        "temporary_redirect" => Response::HTTP_TEMPORARY_REDIRECT,
        "bad_request" => Response::HTTP_BAD_REQUEST,
        "unauthorized" => Response::HTTP_UNAUTHORIZED,
        "forbidden" => Response::HTTP_FORBIDDEN,
        "not_found" => Response::HTTP_NOT_FOUND,
        "method_not_allowed" => Response::HTTP_METHOD_NOT_ALLOWED,
        "not_acceptable" => Response::HTTP_NOT_ACCEPTABLE,
        "precondition_failed" => Response::HTTP_PRECONDITION_FAILED,
        "unsupported_media_type" => Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
        "unprocessable_entity" => Response::HTTP_UNPROCESSABLE_ENTITY,
        "server_error" => Response::HTTP_INTERNAL_SERVER_ERROR,
        "not_implemented" => Response::HTTP_NOT_IMPLEMENTED,
    ];
}


//Pagination Response Helper
function getCaseCollection($builder, array $data)
{
    if ($data['paginated'] ?? null) {
        return $builder->paginate($data['paginated'] ?? 20);
    }
    return $builder->get();
}
