<?php

namespace DeliciousBrains\WP_Offload_Media\Aws3;

// This file was auto-generated from sdk-root/src/data/controltower/2018-05-10/api-2.json
return ['version' => '2.0', 'metadata' => ['apiVersion' => '2018-05-10', 'endpointPrefix' => 'controltower', 'jsonVersion' => '1.1', 'protocol' => 'rest-json', 'serviceFullName' => 'AWS Control Tower', 'serviceId' => 'ControlTower', 'signatureVersion' => 'v4', 'signingName' => 'controltower', 'uid' => 'controltower-2018-05-10'], 'operations' => ['DisableControl' => ['name' => 'DisableControl', 'http' => ['method' => 'POST', 'requestUri' => '/disable-control', 'responseCode' => 200], 'input' => ['shape' => 'DisableControlInput'], 'output' => ['shape' => 'DisableControlOutput'], 'errors' => [['shape' => 'ValidationException'], ['shape' => 'ConflictException'], ['shape' => 'ServiceQuotaExceededException'], ['shape' => 'InternalServerException'], ['shape' => 'AccessDeniedException'], ['shape' => 'ThrottlingException'], ['shape' => 'ResourceNotFoundException']]], 'EnableControl' => ['name' => 'EnableControl', 'http' => ['method' => 'POST', 'requestUri' => '/enable-control', 'responseCode' => 200], 'input' => ['shape' => 'EnableControlInput'], 'output' => ['shape' => 'EnableControlOutput'], 'errors' => [['shape' => 'ValidationException'], ['shape' => 'ConflictException'], ['shape' => 'ServiceQuotaExceededException'], ['shape' => 'InternalServerException'], ['shape' => 'AccessDeniedException'], ['shape' => 'ThrottlingException'], ['shape' => 'ResourceNotFoundException']]], 'GetControlOperation' => ['name' => 'GetControlOperation', 'http' => ['method' => 'POST', 'requestUri' => '/get-control-operation', 'responseCode' => 200], 'input' => ['shape' => 'GetControlOperationInput'], 'output' => ['shape' => 'GetControlOperationOutput'], 'errors' => [['shape' => 'ValidationException'], ['shape' => 'InternalServerException'], ['shape' => 'AccessDeniedException'], ['shape' => 'ThrottlingException'], ['shape' => 'ResourceNotFoundException']]], 'ListEnabledControls' => ['name' => 'ListEnabledControls', 'http' => ['method' => 'POST', 'requestUri' => '/list-enabled-controls', 'responseCode' => 200], 'input' => ['shape' => 'ListEnabledControlsInput'], 'output' => ['shape' => 'ListEnabledControlsOutput'], 'errors' => [['shape' => 'ValidationException'], ['shape' => 'InternalServerException'], ['shape' => 'AccessDeniedException'], ['shape' => 'ThrottlingException'], ['shape' => 'ResourceNotFoundException']]]], 'shapes' => ['AccessDeniedException' => ['type' => 'structure', 'required' => ['message'], 'members' => ['message' => ['shape' => 'String']], 'error' => ['httpStatusCode' => 403, 'senderFault' => \true], 'exception' => \true], 'ConflictException' => ['type' => 'structure', 'required' => ['message'], 'members' => ['message' => ['shape' => 'String']], 'error' => ['httpStatusCode' => 409, 'senderFault' => \true], 'exception' => \true], 'ControlIdentifier' => ['type' => 'string', 'max' => 2048, 'min' => 20, 'pattern' => '^arn:aws[0-9a-zA-Z_\\-:\\/]+$'], 'ControlOperation' => ['type' => 'structure', 'members' => ['endTime' => ['shape' => 'SyntheticTimestamp_date_time'], 'operationType' => ['shape' => 'ControlOperationType'], 'startTime' => ['shape' => 'SyntheticTimestamp_date_time'], 'status' => ['shape' => 'ControlOperationStatus'], 'statusMessage' => ['shape' => 'String']]], 'ControlOperationStatus' => ['type' => 'string', 'enum' => ['SUCCEEDED', 'FAILED', 'IN_PROGRESS']], 'ControlOperationType' => ['type' => 'string', 'enum' => ['ENABLE_CONTROL', 'DISABLE_CONTROL']], 'DisableControlInput' => ['type' => 'structure', 'required' => ['controlIdentifier', 'targetIdentifier'], 'members' => ['controlIdentifier' => ['shape' => 'ControlIdentifier'], 'targetIdentifier' => ['shape' => 'TargetIdentifier']]], 'DisableControlOutput' => ['type' => 'structure', 'required' => ['operationIdentifier'], 'members' => ['operationIdentifier' => ['shape' => 'OperationIdentifier']]], 'EnableControlInput' => ['type' => 'structure', 'required' => ['controlIdentifier', 'targetIdentifier'], 'members' => ['controlIdentifier' => ['shape' => 'ControlIdentifier'], 'targetIdentifier' => ['shape' => 'TargetIdentifier']]], 'EnableControlOutput' => ['type' => 'structure', 'required' => ['operationIdentifier'], 'members' => ['operationIdentifier' => ['shape' => 'OperationIdentifier']]], 'EnabledControlSummary' => ['type' => 'structure', 'members' => ['controlIdentifier' => ['shape' => 'ControlIdentifier']]], 'EnabledControls' => ['type' => 'list', 'member' => ['shape' => 'EnabledControlSummary']], 'GetControlOperationInput' => ['type' => 'structure', 'required' => ['operationIdentifier'], 'members' => ['operationIdentifier' => ['shape' => 'OperationIdentifier']]], 'GetControlOperationOutput' => ['type' => 'structure', 'required' => ['controlOperation'], 'members' => ['controlOperation' => ['shape' => 'ControlOperation']]], 'Integer' => ['type' => 'integer', 'box' => \true], 'InternalServerException' => ['type' => 'structure', 'required' => ['message'], 'members' => ['message' => ['shape' => 'String']], 'error' => ['httpStatusCode' => 500], 'exception' => \true, 'fault' => \true, 'retryable' => ['throttling' => \false]], 'ListEnabledControlsInput' => ['type' => 'structure', 'required' => ['targetIdentifier'], 'members' => ['maxResults' => ['shape' => 'MaxResults'], 'nextToken' => ['shape' => 'String'], 'targetIdentifier' => ['shape' => 'TargetIdentifier']]], 'ListEnabledControlsOutput' => ['type' => 'structure', 'required' => ['enabledControls'], 'members' => ['enabledControls' => ['shape' => 'EnabledControls'], 'nextToken' => ['shape' => 'String']]], 'MaxResults' => ['type' => 'integer', 'box' => \true, 'max' => 100, 'min' => 1], 'OperationIdentifier' => ['type' => 'string', 'max' => 36, 'min' => 36, 'pattern' => '^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$'], 'ResourceNotFoundException' => ['type' => 'structure', 'required' => ['message'], 'members' => ['message' => ['shape' => 'String']], 'error' => ['httpStatusCode' => 404, 'senderFault' => \true], 'exception' => \true], 'ServiceQuotaExceededException' => ['type' => 'structure', 'required' => ['message'], 'members' => ['message' => ['shape' => 'String']], 'error' => ['httpStatusCode' => 402, 'senderFault' => \true], 'exception' => \true], 'String' => ['type' => 'string'], 'SyntheticTimestamp_date_time' => ['type' => 'timestamp', 'timestampFormat' => 'iso8601'], 'TargetIdentifier' => ['type' => 'string', 'max' => 2048, 'min' => 20, 'pattern' => '^arn:aws[0-9a-zA-Z_\\-:\\/]+$'], 'ThrottlingException' => ['type' => 'structure', 'required' => ['message'], 'members' => ['message' => ['shape' => 'String'], 'quotaCode' => ['shape' => 'String'], 'retryAfterSeconds' => ['shape' => 'Integer', 'location' => 'header', 'locationName' => 'Retry-After'], 'serviceCode' => ['shape' => 'String']], 'error' => ['httpStatusCode' => 429, 'senderFault' => \true], 'exception' => \true, 'retryable' => ['throttling' => \true]], 'ValidationException' => ['type' => 'structure', 'required' => ['message'], 'members' => ['message' => ['shape' => 'String']], 'error' => ['httpStatusCode' => 400, 'senderFault' => \true], 'exception' => \true]]];