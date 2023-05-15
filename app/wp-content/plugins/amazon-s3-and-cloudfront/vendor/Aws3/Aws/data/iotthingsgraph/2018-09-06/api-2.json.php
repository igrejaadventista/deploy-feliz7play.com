<?php

namespace DeliciousBrains\WP_Offload_Media\Aws3;

// This file was auto-generated from sdk-root/src/data/iotthingsgraph/2018-09-06/api-2.json
return ['version' => '2.0', 'metadata' => ['apiVersion' => '2018-09-06', 'endpointPrefix' => 'iotthingsgraph', 'jsonVersion' => '1.1', 'protocol' => 'json', 'serviceFullName' => 'AWS IoT Things Graph', 'serviceId' => 'IoTThingsGraph', 'signatureVersion' => 'v4', 'signingName' => 'iotthingsgraph', 'targetPrefix' => 'IotThingsGraphFrontEndService', 'uid' => 'iotthingsgraph-2018-09-06'], 'operations' => ['AssociateEntityToThing' => ['name' => 'AssociateEntityToThing', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'AssociateEntityToThingRequest'], 'output' => ['shape' => 'AssociateEntityToThingResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceNotFoundException'], ['shape' => 'InternalFailureException'], ['shape' => 'ThrottlingException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'CreateFlowTemplate' => ['name' => 'CreateFlowTemplate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'CreateFlowTemplateRequest'], 'output' => ['shape' => 'CreateFlowTemplateResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceAlreadyExistsException'], ['shape' => 'ThrottlingException'], ['shape' => 'LimitExceededException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'CreateSystemInstance' => ['name' => 'CreateSystemInstance', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'CreateSystemInstanceRequest'], 'output' => ['shape' => 'CreateSystemInstanceResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceAlreadyExistsException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'LimitExceededException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'CreateSystemTemplate' => ['name' => 'CreateSystemTemplate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'CreateSystemTemplateRequest'], 'output' => ['shape' => 'CreateSystemTemplateResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceAlreadyExistsException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'DeleteFlowTemplate' => ['name' => 'DeleteFlowTemplate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeleteFlowTemplateRequest'], 'output' => ['shape' => 'DeleteFlowTemplateResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceInUseException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'DeleteNamespace' => ['name' => 'DeleteNamespace', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeleteNamespaceRequest'], 'output' => ['shape' => 'DeleteNamespaceResponse'], 'errors' => [['shape' => 'InternalFailureException'], ['shape' => 'ThrottlingException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'DeleteSystemInstance' => ['name' => 'DeleteSystemInstance', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeleteSystemInstanceRequest'], 'output' => ['shape' => 'DeleteSystemInstanceResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceInUseException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'DeleteSystemTemplate' => ['name' => 'DeleteSystemTemplate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeleteSystemTemplateRequest'], 'output' => ['shape' => 'DeleteSystemTemplateResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceInUseException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'DeploySystemInstance' => ['name' => 'DeploySystemInstance', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeploySystemInstanceRequest'], 'output' => ['shape' => 'DeploySystemInstanceResponse'], 'errors' => [['shape' => 'ResourceNotFoundException'], ['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceInUseException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'DeprecateFlowTemplate' => ['name' => 'DeprecateFlowTemplate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeprecateFlowTemplateRequest'], 'output' => ['shape' => 'DeprecateFlowTemplateResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceNotFoundException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'DeprecateSystemTemplate' => ['name' => 'DeprecateSystemTemplate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DeprecateSystemTemplateRequest'], 'output' => ['shape' => 'DeprecateSystemTemplateResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceNotFoundException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'DescribeNamespace' => ['name' => 'DescribeNamespace', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DescribeNamespaceRequest'], 'output' => ['shape' => 'DescribeNamespaceResponse'], 'errors' => [['shape' => 'ResourceNotFoundException'], ['shape' => 'InvalidRequestException'], ['shape' => 'InternalFailureException'], ['shape' => 'ThrottlingException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'DissociateEntityFromThing' => ['name' => 'DissociateEntityFromThing', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'DissociateEntityFromThingRequest'], 'output' => ['shape' => 'DissociateEntityFromThingResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceNotFoundException'], ['shape' => 'InternalFailureException'], ['shape' => 'ThrottlingException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'GetEntities' => ['name' => 'GetEntities', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'GetEntitiesRequest'], 'output' => ['shape' => 'GetEntitiesResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceNotFoundException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'GetFlowTemplate' => ['name' => 'GetFlowTemplate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'GetFlowTemplateRequest'], 'output' => ['shape' => 'GetFlowTemplateResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceNotFoundException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'GetFlowTemplateRevisions' => ['name' => 'GetFlowTemplateRevisions', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'GetFlowTemplateRevisionsRequest'], 'output' => ['shape' => 'GetFlowTemplateRevisionsResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceNotFoundException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'GetNamespaceDeletionStatus' => ['name' => 'GetNamespaceDeletionStatus', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'GetNamespaceDeletionStatusRequest'], 'output' => ['shape' => 'GetNamespaceDeletionStatusResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'InternalFailureException'], ['shape' => 'ThrottlingException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'GetSystemInstance' => ['name' => 'GetSystemInstance', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'GetSystemInstanceRequest'], 'output' => ['shape' => 'GetSystemInstanceResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceNotFoundException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'GetSystemTemplate' => ['name' => 'GetSystemTemplate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'GetSystemTemplateRequest'], 'output' => ['shape' => 'GetSystemTemplateResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceNotFoundException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'GetSystemTemplateRevisions' => ['name' => 'GetSystemTemplateRevisions', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'GetSystemTemplateRevisionsRequest'], 'output' => ['shape' => 'GetSystemTemplateRevisionsResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceNotFoundException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'GetUploadStatus' => ['name' => 'GetUploadStatus', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'GetUploadStatusRequest'], 'output' => ['shape' => 'GetUploadStatusResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceNotFoundException'], ['shape' => 'InternalFailureException'], ['shape' => 'ThrottlingException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'ListFlowExecutionMessages' => ['name' => 'ListFlowExecutionMessages', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'ListFlowExecutionMessagesRequest'], 'output' => ['shape' => 'ListFlowExecutionMessagesResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceNotFoundException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'ListTagsForResource' => ['name' => 'ListTagsForResource', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'ListTagsForResourceRequest'], 'output' => ['shape' => 'ListTagsForResourceResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceAlreadyExistsException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'SearchEntities' => ['name' => 'SearchEntities', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'SearchEntitiesRequest'], 'output' => ['shape' => 'SearchEntitiesResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'InternalFailureException'], ['shape' => 'ThrottlingException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'SearchFlowExecutions' => ['name' => 'SearchFlowExecutions', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'SearchFlowExecutionsRequest'], 'output' => ['shape' => 'SearchFlowExecutionsResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceNotFoundException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'SearchFlowTemplates' => ['name' => 'SearchFlowTemplates', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'SearchFlowTemplatesRequest'], 'output' => ['shape' => 'SearchFlowTemplatesResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'SearchSystemInstances' => ['name' => 'SearchSystemInstances', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'SearchSystemInstancesRequest'], 'output' => ['shape' => 'SearchSystemInstancesResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'SearchSystemTemplates' => ['name' => 'SearchSystemTemplates', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'SearchSystemTemplatesRequest'], 'output' => ['shape' => 'SearchSystemTemplatesResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'SearchThings' => ['name' => 'SearchThings', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'SearchThingsRequest'], 'output' => ['shape' => 'SearchThingsResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceNotFoundException'], ['shape' => 'InternalFailureException'], ['shape' => 'ThrottlingException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'TagResource' => ['name' => 'TagResource', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'TagResourceRequest'], 'output' => ['shape' => 'TagResourceResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceAlreadyExistsException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'UndeploySystemInstance' => ['name' => 'UndeploySystemInstance', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'UndeploySystemInstanceRequest'], 'output' => ['shape' => 'UndeploySystemInstanceResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException'], ['shape' => 'ResourceNotFoundException'], ['shape' => 'ResourceInUseException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'UntagResource' => ['name' => 'UntagResource', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'UntagResourceRequest'], 'output' => ['shape' => 'UntagResourceResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceAlreadyExistsException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'UpdateFlowTemplate' => ['name' => 'UpdateFlowTemplate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'UpdateFlowTemplateRequest'], 'output' => ['shape' => 'UpdateFlowTemplateResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceNotFoundException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'UpdateSystemTemplate' => ['name' => 'UpdateSystemTemplate', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'UpdateSystemTemplateRequest'], 'output' => ['shape' => 'UpdateSystemTemplateResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'ResourceNotFoundException'], ['shape' => 'ThrottlingException'], ['shape' => 'InternalFailureException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30'], 'UploadEntityDefinitions' => ['name' => 'UploadEntityDefinitions', 'http' => ['method' => 'POST', 'requestUri' => '/'], 'input' => ['shape' => 'UploadEntityDefinitionsRequest'], 'output' => ['shape' => 'UploadEntityDefinitionsResponse'], 'errors' => [['shape' => 'InvalidRequestException'], ['shape' => 'InternalFailureException'], ['shape' => 'ThrottlingException']], 'deprecated' => \true, 'deprecatedMessage' => 'since: 2022-08-30']], 'shapes' => ['Arn' => ['type' => 'string'], 'AssociateEntityToThingRequest' => ['type' => 'structure', 'required' => ['thingName', 'entityId'], 'members' => ['thingName' => ['shape' => 'ThingName'], 'entityId' => ['shape' => 'Urn'], 'namespaceVersion' => ['shape' => 'Version']]], 'AssociateEntityToThingResponse' => ['type' => 'structure', 'members' => []], 'CreateFlowTemplateRequest' => ['type' => 'structure', 'required' => ['definition'], 'members' => ['definition' => ['shape' => 'DefinitionDocument'], 'compatibleNamespaceVersion' => ['shape' => 'Version']]], 'CreateFlowTemplateResponse' => ['type' => 'structure', 'members' => ['summary' => ['shape' => 'FlowTemplateSummary']]], 'CreateSystemInstanceRequest' => ['type' => 'structure', 'required' => ['definition', 'target'], 'members' => ['tags' => ['shape' => 'TagList'], 'definition' => ['shape' => 'DefinitionDocument'], 'target' => ['shape' => 'DeploymentTarget'], 'greengrassGroupName' => ['shape' => 'GroupName'], 's3BucketName' => ['shape' => 'S3BucketName'], 'metricsConfiguration' => ['shape' => 'MetricsConfiguration'], 'flowActionsRoleArn' => ['shape' => 'RoleArn']]], 'CreateSystemInstanceResponse' => ['type' => 'structure', 'members' => ['summary' => ['shape' => 'SystemInstanceSummary']]], 'CreateSystemTemplateRequest' => ['type' => 'structure', 'required' => ['definition'], 'members' => ['definition' => ['shape' => 'DefinitionDocument'], 'compatibleNamespaceVersion' => ['shape' => 'Version']]], 'CreateSystemTemplateResponse' => ['type' => 'structure', 'members' => ['summary' => ['shape' => 'SystemTemplateSummary']]], 'DefinitionDocument' => ['type' => 'structure', 'required' => ['language', 'text'], 'members' => ['language' => ['shape' => 'DefinitionLanguage'], 'text' => ['shape' => 'DefinitionText']]], 'DefinitionLanguage' => ['type' => 'string', 'enum' => ['GRAPHQL']], 'DefinitionText' => ['type' => 'string', 'max' => 1048576], 'DeleteFlowTemplateRequest' => ['type' => 'structure', 'required' => ['id'], 'members' => ['id' => ['shape' => 'Urn']]], 'DeleteFlowTemplateResponse' => ['type' => 'structure', 'members' => []], 'DeleteNamespaceRequest' => ['type' => 'structure', 'members' => []], 'DeleteNamespaceResponse' => ['type' => 'structure', 'members' => ['namespaceArn' => ['shape' => 'Arn'], 'namespaceName' => ['shape' => 'NamespaceName']]], 'DeleteSystemInstanceRequest' => ['type' => 'structure', 'members' => ['id' => ['shape' => 'Urn']]], 'DeleteSystemInstanceResponse' => ['type' => 'structure', 'members' => []], 'DeleteSystemTemplateRequest' => ['type' => 'structure', 'required' => ['id'], 'members' => ['id' => ['shape' => 'Urn']]], 'DeleteSystemTemplateResponse' => ['type' => 'structure', 'members' => []], 'DependencyRevision' => ['type' => 'structure', 'members' => ['id' => ['shape' => 'Urn'], 'revisionNumber' => ['shape' => 'Version']]], 'DependencyRevisions' => ['type' => 'list', 'member' => ['shape' => 'DependencyRevision']], 'DeploySystemInstanceRequest' => ['type' => 'structure', 'members' => ['id' => ['shape' => 'Urn']]], 'DeploySystemInstanceResponse' => ['type' => 'structure', 'required' => ['summary'], 'members' => ['summary' => ['shape' => 'SystemInstanceSummary'], 'greengrassDeploymentId' => ['shape' => 'GreengrassDeploymentId']]], 'DeploymentTarget' => ['type' => 'string', 'enum' => ['GREENGRASS', 'CLOUD']], 'DeprecateExistingEntities' => ['type' => 'boolean'], 'DeprecateFlowTemplateRequest' => ['type' => 'structure', 'required' => ['id'], 'members' => ['id' => ['shape' => 'Urn']]], 'DeprecateFlowTemplateResponse' => ['type' => 'structure', 'members' => []], 'DeprecateSystemTemplateRequest' => ['type' => 'structure', 'required' => ['id'], 'members' => ['id' => ['shape' => 'Urn']]], 'DeprecateSystemTemplateResponse' => ['type' => 'structure', 'members' => []], 'DescribeNamespaceRequest' => ['type' => 'structure', 'members' => ['namespaceName' => ['shape' => 'NamespaceName']]], 'DescribeNamespaceResponse' => ['type' => 'structure', 'members' => ['namespaceArn' => ['shape' => 'Arn'], 'namespaceName' => ['shape' => 'NamespaceName'], 'trackingNamespaceName' => ['shape' => 'NamespaceName'], 'trackingNamespaceVersion' => ['shape' => 'Version'], 'namespaceVersion' => ['shape' => 'Version']]], 'DissociateEntityFromThingRequest' => ['type' => 'structure', 'required' => ['thingName', 'entityType'], 'members' => ['thingName' => ['shape' => 'ThingName'], 'entityType' => ['shape' => 'EntityType']]], 'DissociateEntityFromThingResponse' => ['type' => 'structure', 'members' => []], 'Enabled' => ['type' => 'boolean'], 'EntityDescription' => ['type' => 'structure', 'members' => ['id' => ['shape' => 'Urn'], 'arn' => ['shape' => 'Arn'], 'type' => ['shape' => 'EntityType'], 'createdAt' => ['shape' => 'Timestamp'], 'definition' => ['shape' => 'DefinitionDocument']]], 'EntityDescriptions' => ['type' => 'list', 'member' => ['shape' => 'EntityDescription']], 'EntityFilter' => ['type' => 'structure', 'members' => ['name' => ['shape' => 'EntityFilterName'], 'value' => ['shape' => 'EntityFilterValues']]], 'EntityFilterName' => ['type' => 'string', 'enum' => ['NAME', 'NAMESPACE', 'SEMANTIC_TYPE_PATH', 'REFERENCED_ENTITY_ID']], 'EntityFilterValue' => ['type' => 'string'], 'EntityFilterValues' => ['type' => 'list', 'member' => ['shape' => 'EntityFilterValue']], 'EntityFilters' => ['type' => 'list', 'member' => ['shape' => 'EntityFilter']], 'EntityType' => ['type' => 'string', 'enum' => ['DEVICE', 'SERVICE', 'DEVICE_MODEL', 'CAPABILITY', 'STATE', 'ACTION', 'EVENT', 'PROPERTY', 'MAPPING', 'ENUM']], 'EntityTypes' => ['type' => 'list', 'member' => ['shape' => 'EntityType']], 'ErrorMessage' => ['type' => 'string', 'max' => 2048], 'FlowExecutionEventType' => ['type' => 'string', 'enum' => ['EXECUTION_STARTED', 'EXECUTION_FAILED', 'EXECUTION_ABORTED', 'EXECUTION_SUCCEEDED', 'STEP_STARTED', 'STEP_FAILED', 'STEP_SUCCEEDED', 'ACTIVITY_SCHEDULED', 'ACTIVITY_STARTED', 'ACTIVITY_FAILED', 'ACTIVITY_SUCCEEDED', 'START_FLOW_EXECUTION_TASK', 'SCHEDULE_NEXT_READY_STEPS_TASK', 'THING_ACTION_TASK', 'THING_ACTION_TASK_FAILED', 'THING_ACTION_TASK_SUCCEEDED', 'ACKNOWLEDGE_TASK_MESSAGE']], 'FlowExecutionId' => ['type' => 'string'], 'FlowExecutionMessage' => ['type' => 'structure', 'members' => ['messageId' => ['shape' => 'FlowExecutionMessageId'], 'eventType' => ['shape' => 'FlowExecutionEventType'], 'timestamp' => ['shape' => 'Timestamp'], 'payload' => ['shape' => 'FlowExecutionMessagePayload']]], 'FlowExecutionMessageId' => ['type' => 'string'], 'FlowExecutionMessagePayload' => ['type' => 'string'], 'FlowExecutionMessages' => ['type' => 'list', 'member' => ['shape' => 'FlowExecutionMessage']], 'FlowExecutionStatus' => ['type' => 'string', 'enum' => ['RUNNING', 'ABORTED', 'SUCCEEDED', 'FAILED']], 'FlowExecutionSummaries' => ['type' => 'list', 'member' => ['shape' => 'FlowExecutionSummary']], 'FlowExecutionSummary' => ['type' => 'structure', 'members' => ['flowExecutionId' => ['shape' => 'FlowExecutionId'], 'status' => ['shape' => 'FlowExecutionStatus'], 'systemInstanceId' => ['shape' => 'Urn'], 'flowTemplateId' => ['shape' => 'Urn'], 'createdAt' => ['shape' => 'Timestamp'], 'updatedAt' => ['shape' => 'Timestamp']]], 'FlowTemplateDescription' => ['type' => 'structure', 'members' => ['summary' => ['shape' => 'FlowTemplateSummary'], 'definition' => ['shape' => 'DefinitionDocument'], 'validatedNamespaceVersion' => ['shape' => 'Version']]], 'FlowTemplateFilter' => ['type' => 'structure', 'required' => ['name', 'value'], 'members' => ['name' => ['shape' => 'FlowTemplateFilterName'], 'value' => ['shape' => 'FlowTemplateFilterValues']]], 'FlowTemplateFilterName' => ['type' => 'string', 'enum' => ['DEVICE_MODEL_ID']], 'FlowTemplateFilterValue' => ['type' => 'string', 'pattern' => '^urn:tdm:(([a-z]{2}-(gov-)?[a-z]{4,9}-[0-9]{1,3}/[0-9]+/)*[\\p{Alnum}_]+(/[\\p{Alnum}_]+)*):([\\p{Alpha}]*):([\\p{Alnum}_]+(/[\\p{Alnum}_]+)*)$'], 'FlowTemplateFilterValues' => ['type' => 'list', 'member' => ['shape' => 'FlowTemplateFilterValue']], 'FlowTemplateFilters' => ['type' => 'list', 'member' => ['shape' => 'FlowTemplateFilter']], 'FlowTemplateSummaries' => ['type' => 'list', 'member' => ['shape' => 'FlowTemplateSummary']], 'FlowTemplateSummary' => ['type' => 'structure', 'members' => ['id' => ['shape' => 'Urn'], 'arn' => ['shape' => 'Arn'], 'revisionNumber' => ['shape' => 'Version'], 'createdAt' => ['shape' => 'Timestamp']]], 'GetEntitiesRequest' => ['type' => 'structure', 'required' => ['ids'], 'members' => ['ids' => ['shape' => 'Urns'], 'namespaceVersion' => ['shape' => 'Version']]], 'GetEntitiesResponse' => ['type' => 'structure', 'members' => ['descriptions' => ['shape' => 'EntityDescriptions']]], 'GetFlowTemplateRequest' => ['type' => 'structure', 'required' => ['id'], 'members' => ['id' => ['shape' => 'Urn'], 'revisionNumber' => ['shape' => 'Version']]], 'GetFlowTemplateResponse' => ['type' => 'structure', 'members' => ['description' => ['shape' => 'FlowTemplateDescription']]], 'GetFlowTemplateRevisionsRequest' => ['type' => 'structure', 'required' => ['id'], 'members' => ['id' => ['shape' => 'Urn'], 'nextToken' => ['shape' => 'NextToken'], 'maxResults' => ['shape' => 'MaxResults']]], 'GetFlowTemplateRevisionsResponse' => ['type' => 'structure', 'members' => ['summaries' => ['shape' => 'FlowTemplateSummaries'], 'nextToken' => ['shape' => 'NextToken']]], 'GetNamespaceDeletionStatusRequest' => ['type' => 'structure', 'members' => []], 'GetNamespaceDeletionStatusResponse' => ['type' => 'structure', 'members' => ['namespaceArn' => ['shape' => 'Arn'], 'namespaceName' => ['shape' => 'NamespaceName'], 'status' => ['shape' => 'NamespaceDeletionStatus'], 'errorCode' => ['shape' => 'NamespaceDeletionStatusErrorCodes'], 'errorMessage' => ['shape' => 'String']]], 'GetSystemInstanceRequest' => ['type' => 'structure', 'required' => ['id'], 'members' => ['id' => ['shape' => 'Urn']]], 'GetSystemInstanceResponse' => ['type' => 'structure', 'members' => ['description' => ['shape' => 'SystemInstanceDescription']]], 'GetSystemTemplateRequest' => ['type' => 'structure', 'required' => ['id'], 'members' => ['id' => ['shape' => 'Urn'], 'revisionNumber' => ['shape' => 'Version']]], 'GetSystemTemplateResponse' => ['type' => 'structure', 'members' => ['description' => ['shape' => 'SystemTemplateDescription']]], 'GetSystemTemplateRevisionsRequest' => ['type' => 'structure', 'required' => ['id'], 'members' => ['id' => ['shape' => 'Urn'], 'nextToken' => ['shape' => 'NextToken'], 'maxResults' => ['shape' => 'MaxResults']]], 'GetSystemTemplateRevisionsResponse' => ['type' => 'structure', 'members' => ['summaries' => ['shape' => 'SystemTemplateSummaries'], 'nextToken' => ['shape' => 'NextToken']]], 'GetUploadStatusRequest' => ['type' => 'structure', 'required' => ['uploadId'], 'members' => ['uploadId' => ['shape' => 'UploadId']]], 'GetUploadStatusResponse' => ['type' => 'structure', 'required' => ['uploadId', 'uploadStatus', 'createdDate'], 'members' => ['uploadId' => ['shape' => 'UploadId'], 'uploadStatus' => ['shape' => 'UploadStatus'], 'namespaceArn' => ['shape' => 'Arn'], 'namespaceName' => ['shape' => 'NamespaceName'], 'namespaceVersion' => ['shape' => 'Version'], 'failureReason' => ['shape' => 'StringList'], 'createdDate' => ['shape' => 'Timestamp']]], 'GreengrassDeploymentId' => ['type' => 'string'], 'GreengrassGroupId' => ['type' => 'string'], 'GreengrassGroupVersionId' => ['type' => 'string'], 'GroupName' => ['type' => 'string'], 'InternalFailureException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'ErrorMessage']], 'exception' => \true, 'fault' => \true], 'InvalidRequestException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'ErrorMessage']], 'exception' => \true], 'LimitExceededException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'ErrorMessage']], 'exception' => \true], 'ListFlowExecutionMessagesRequest' => ['type' => 'structure', 'required' => ['flowExecutionId'], 'members' => ['flowExecutionId' => ['shape' => 'FlowExecutionId'], 'nextToken' => ['shape' => 'NextToken'], 'maxResults' => ['shape' => 'MaxResults']]], 'ListFlowExecutionMessagesResponse' => ['type' => 'structure', 'members' => ['messages' => ['shape' => 'FlowExecutionMessages'], 'nextToken' => ['shape' => 'NextToken']]], 'ListTagsForResourceRequest' => ['type' => 'structure', 'required' => ['resourceArn'], 'members' => ['maxResults' => ['shape' => 'MaxResults'], 'resourceArn' => ['shape' => 'ResourceArn'], 'nextToken' => ['shape' => 'NextToken']]], 'ListTagsForResourceResponse' => ['type' => 'structure', 'members' => ['tags' => ['shape' => 'TagList'], 'nextToken' => ['shape' => 'NextToken']]], 'MaxResults' => ['type' => 'integer', 'max' => 250, 'min' => 1], 'MetricsConfiguration' => ['type' => 'structure', 'members' => ['cloudMetricEnabled' => ['shape' => 'Enabled'], 'metricRuleRoleArn' => ['shape' => 'RoleArn']]], 'NamespaceDeletionStatus' => ['type' => 'string', 'enum' => ['IN_PROGRESS', 'SUCCEEDED', 'FAILED']], 'NamespaceDeletionStatusErrorCodes' => ['type' => 'string', 'enum' => ['VALIDATION_FAILED']], 'NamespaceName' => ['type' => 'string', 'max' => 128], 'NextToken' => ['type' => 'string'], 'ResourceAlreadyExistsException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'ErrorMessage']], 'exception' => \true], 'ResourceArn' => ['type' => 'string', 'max' => 2048, 'min' => 1], 'ResourceInUseException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'ErrorMessage']], 'exception' => \true], 'ResourceNotFoundException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'ErrorMessage']], 'exception' => \true], 'RoleArn' => ['type' => 'string', 'max' => 2048, 'min' => 20], 'S3BucketName' => ['type' => 'string'], 'SearchEntitiesRequest' => ['type' => 'structure', 'required' => ['entityTypes'], 'members' => ['entityTypes' => ['shape' => 'EntityTypes'], 'filters' => ['shape' => 'EntityFilters'], 'nextToken' => ['shape' => 'NextToken'], 'maxResults' => ['shape' => 'MaxResults'], 'namespaceVersion' => ['shape' => 'Version']]], 'SearchEntitiesResponse' => ['type' => 'structure', 'members' => ['descriptions' => ['shape' => 'EntityDescriptions'], 'nextToken' => ['shape' => 'NextToken']]], 'SearchFlowExecutionsRequest' => ['type' => 'structure', 'required' => ['systemInstanceId'], 'members' => ['systemInstanceId' => ['shape' => 'Urn'], 'flowExecutionId' => ['shape' => 'FlowExecutionId'], 'startTime' => ['shape' => 'Timestamp'], 'endTime' => ['shape' => 'Timestamp'], 'nextToken' => ['shape' => 'NextToken'], 'maxResults' => ['shape' => 'MaxResults']]], 'SearchFlowExecutionsResponse' => ['type' => 'structure', 'members' => ['summaries' => ['shape' => 'FlowExecutionSummaries'], 'nextToken' => ['shape' => 'NextToken']]], 'SearchFlowTemplatesRequest' => ['type' => 'structure', 'members' => ['filters' => ['shape' => 'FlowTemplateFilters'], 'nextToken' => ['shape' => 'NextToken'], 'maxResults' => ['shape' => 'MaxResults']]], 'SearchFlowTemplatesResponse' => ['type' => 'structure', 'members' => ['summaries' => ['shape' => 'FlowTemplateSummaries'], 'nextToken' => ['shape' => 'NextToken']]], 'SearchSystemInstancesRequest' => ['type' => 'structure', 'members' => ['filters' => ['shape' => 'SystemInstanceFilters'], 'nextToken' => ['shape' => 'NextToken'], 'maxResults' => ['shape' => 'MaxResults']]], 'SearchSystemInstancesResponse' => ['type' => 'structure', 'members' => ['summaries' => ['shape' => 'SystemInstanceSummaries'], 'nextToken' => ['shape' => 'NextToken']]], 'SearchSystemTemplatesRequest' => ['type' => 'structure', 'members' => ['filters' => ['shape' => 'SystemTemplateFilters'], 'nextToken' => ['shape' => 'NextToken'], 'maxResults' => ['shape' => 'MaxResults']]], 'SearchSystemTemplatesResponse' => ['type' => 'structure', 'members' => ['summaries' => ['shape' => 'SystemTemplateSummaries'], 'nextToken' => ['shape' => 'NextToken']]], 'SearchThingsRequest' => ['type' => 'structure', 'required' => ['entityId'], 'members' => ['entityId' => ['shape' => 'Urn'], 'nextToken' => ['shape' => 'NextToken'], 'maxResults' => ['shape' => 'MaxResults'], 'namespaceVersion' => ['shape' => 'Version']]], 'SearchThingsResponse' => ['type' => 'structure', 'members' => ['things' => ['shape' => 'Things'], 'nextToken' => ['shape' => 'NextToken']]], 'String' => ['type' => 'string'], 'StringList' => ['type' => 'list', 'member' => ['shape' => 'String']], 'SyncWithPublicNamespace' => ['type' => 'boolean'], 'SystemInstanceDeploymentStatus' => ['type' => 'string', 'enum' => ['NOT_DEPLOYED', 'BOOTSTRAP', 'DEPLOY_IN_PROGRESS', 'DEPLOYED_IN_TARGET', 'UNDEPLOY_IN_PROGRESS', 'FAILED', 'PENDING_DELETE', 'DELETED_IN_TARGET']], 'SystemInstanceDescription' => ['type' => 'structure', 'members' => ['summary' => ['shape' => 'SystemInstanceSummary'], 'definition' => ['shape' => 'DefinitionDocument'], 's3BucketName' => ['shape' => 'S3BucketName'], 'metricsConfiguration' => ['shape' => 'MetricsConfiguration'], 'validatedNamespaceVersion' => ['shape' => 'Version'], 'validatedDependencyRevisions' => ['shape' => 'DependencyRevisions'], 'flowActionsRoleArn' => ['shape' => 'RoleArn']]], 'SystemInstanceFilter' => ['type' => 'structure', 'members' => ['name' => ['shape' => 'SystemInstanceFilterName'], 'value' => ['shape' => 'SystemInstanceFilterValues']]], 'SystemInstanceFilterName' => ['type' => 'string', 'enum' => ['SYSTEM_TEMPLATE_ID', 'STATUS', 'GREENGRASS_GROUP_NAME']], 'SystemInstanceFilterValue' => ['type' => 'string'], 'SystemInstanceFilterValues' => ['type' => 'list', 'member' => ['shape' => 'SystemInstanceFilterValue']], 'SystemInstanceFilters' => ['type' => 'list', 'member' => ['shape' => 'SystemInstanceFilter']], 'SystemInstanceSummaries' => ['type' => 'list', 'member' => ['shape' => 'SystemInstanceSummary']], 'SystemInstanceSummary' => ['type' => 'structure', 'members' => ['id' => ['shape' => 'Urn'], 'arn' => ['shape' => 'Arn'], 'status' => ['shape' => 'SystemInstanceDeploymentStatus'], 'target' => ['shape' => 'DeploymentTarget'], 'greengrassGroupName' => ['shape' => 'GroupName'], 'createdAt' => ['shape' => 'Timestamp'], 'updatedAt' => ['shape' => 'Timestamp'], 'greengrassGroupId' => ['shape' => 'GreengrassGroupId'], 'greengrassGroupVersionId' => ['shape' => 'GreengrassGroupVersionId']]], 'SystemTemplateDescription' => ['type' => 'structure', 'members' => ['summary' => ['shape' => 'SystemTemplateSummary'], 'definition' => ['shape' => 'DefinitionDocument'], 'validatedNamespaceVersion' => ['shape' => 'Version']]], 'SystemTemplateFilter' => ['type' => 'structure', 'required' => ['name', 'value'], 'members' => ['name' => ['shape' => 'SystemTemplateFilterName'], 'value' => ['shape' => 'SystemTemplateFilterValues']]], 'SystemTemplateFilterName' => ['type' => 'string', 'enum' => ['FLOW_TEMPLATE_ID']], 'SystemTemplateFilterValue' => ['type' => 'string', 'pattern' => '^urn:tdm:(([a-z]{2}-(gov-)?[a-z]{4,9}-[0-9]{1,3}/[0-9]+/)*[\\p{Alnum}_]+(/[\\p{Alnum}_]+)*):([\\p{Alpha}]*):([\\p{Alnum}_]+(/[\\p{Alnum}_]+)*)$'], 'SystemTemplateFilterValues' => ['type' => 'list', 'member' => ['shape' => 'SystemTemplateFilterValue']], 'SystemTemplateFilters' => ['type' => 'list', 'member' => ['shape' => 'SystemTemplateFilter']], 'SystemTemplateSummaries' => ['type' => 'list', 'member' => ['shape' => 'SystemTemplateSummary']], 'SystemTemplateSummary' => ['type' => 'structure', 'members' => ['id' => ['shape' => 'Urn'], 'arn' => ['shape' => 'Arn'], 'revisionNumber' => ['shape' => 'Version'], 'createdAt' => ['shape' => 'Timestamp']]], 'Tag' => ['type' => 'structure', 'required' => ['key', 'value'], 'members' => ['key' => ['shape' => 'TagKey'], 'value' => ['shape' => 'TagValue']]], 'TagKey' => ['type' => 'string', 'max' => 128, 'min' => 1, 'pattern' => '^([\\p{L}\\p{Z}\\p{N}_.:/=+\\-@]*)$'], 'TagKeyList' => ['type' => 'list', 'member' => ['shape' => 'TagKey'], 'max' => 50, 'min' => 1], 'TagList' => ['type' => 'list', 'member' => ['shape' => 'Tag'], 'max' => 50, 'min' => 0], 'TagResourceRequest' => ['type' => 'structure', 'required' => ['resourceArn', 'tags'], 'members' => ['resourceArn' => ['shape' => 'ResourceArn'], 'tags' => ['shape' => 'TagList']]], 'TagResourceResponse' => ['type' => 'structure', 'members' => []], 'TagValue' => ['type' => 'string', 'max' => 256, 'min' => 1], 'Thing' => ['type' => 'structure', 'members' => ['thingArn' => ['shape' => 'ThingArn'], 'thingName' => ['shape' => 'ThingName']]], 'ThingArn' => ['type' => 'string'], 'ThingName' => ['type' => 'string', 'max' => 128, 'min' => 1, 'pattern' => '[a-zA-Z0-9:_-]+'], 'Things' => ['type' => 'list', 'member' => ['shape' => 'Thing']], 'ThrottlingException' => ['type' => 'structure', 'members' => ['message' => ['shape' => 'ErrorMessage']], 'exception' => \true], 'Timestamp' => ['type' => 'timestamp'], 'UndeploySystemInstanceRequest' => ['type' => 'structure', 'members' => ['id' => ['shape' => 'Urn']]], 'UndeploySystemInstanceResponse' => ['type' => 'structure', 'members' => ['summary' => ['shape' => 'SystemInstanceSummary']]], 'UntagResourceRequest' => ['type' => 'structure', 'required' => ['resourceArn', 'tagKeys'], 'members' => ['resourceArn' => ['shape' => 'ResourceArn'], 'tagKeys' => ['shape' => 'TagKeyList']]], 'UntagResourceResponse' => ['type' => 'structure', 'members' => []], 'UpdateFlowTemplateRequest' => ['type' => 'structure', 'required' => ['id', 'definition'], 'members' => ['id' => ['shape' => 'Urn'], 'definition' => ['shape' => 'DefinitionDocument'], 'compatibleNamespaceVersion' => ['shape' => 'Version']]], 'UpdateFlowTemplateResponse' => ['type' => 'structure', 'members' => ['summary' => ['shape' => 'FlowTemplateSummary']]], 'UpdateSystemTemplateRequest' => ['type' => 'structure', 'required' => ['id', 'definition'], 'members' => ['id' => ['shape' => 'Urn'], 'definition' => ['shape' => 'DefinitionDocument'], 'compatibleNamespaceVersion' => ['shape' => 'Version']]], 'UpdateSystemTemplateResponse' => ['type' => 'structure', 'members' => ['summary' => ['shape' => 'SystemTemplateSummary']]], 'UploadEntityDefinitionsRequest' => ['type' => 'structure', 'members' => ['document' => ['shape' => 'DefinitionDocument'], 'syncWithPublicNamespace' => ['shape' => 'SyncWithPublicNamespace'], 'deprecateExistingEntities' => ['shape' => 'DeprecateExistingEntities']]], 'UploadEntityDefinitionsResponse' => ['type' => 'structure', 'required' => ['uploadId'], 'members' => ['uploadId' => ['shape' => 'UploadId']]], 'UploadId' => ['type' => 'string', 'max' => 40, 'min' => 1], 'UploadStatus' => ['type' => 'string', 'enum' => ['IN_PROGRESS', 'SUCCEEDED', 'FAILED']], 'Urn' => ['type' => 'string', 'max' => 160, 'pattern' => '^urn:tdm:(([a-z]{2}-(gov-)?[a-z]{4,9}-[0-9]{1,3}/[0-9]+/)*[\\p{Alnum}_]+(/[\\p{Alnum}_]+)*):([\\p{Alpha}]*):([\\p{Alnum}_]+(/[\\p{Alnum}_]+)*)$'], 'Urns' => ['type' => 'list', 'member' => ['shape' => 'Urn'], 'max' => 25, 'min' => 0], 'Version' => ['type' => 'long']]];
