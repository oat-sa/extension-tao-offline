<?php

namespace oat\taoOffline\model\form;

use core_kernel_classes_Class;
use core_kernel_classes_Resource;
use oat\taoOffline\model\service\TestCenterFormService;
use oat\taoSync\model\synchronizer\custom\byOrganisationId\testcenter\TestCenterByOrganisationId;
use tao_actions_form_Instance;
use tao_helpers_form_FormFactory;
use tao_helpers_Uri;

class TestCenterForm extends tao_actions_form_Instance
{
    /**
     * @var TestCenterFormService
     */
    private $testCenterFormService;

    /**
     * @param TestCenterFormService $testCenterFormService
     * @param core_kernel_classes_Class $clazz
     * @param core_kernel_classes_Resource|null $instance
     * @param array $options
     * @return mixed
     */
    public function __construct (
        TestCenterFormService $testCenterFormService,
        core_kernel_classes_Class $clazz,
        core_kernel_classes_Resource $instance = null,
        $options = array()
    ) {
        $this->testCenterFormService = $testCenterFormService;
        parent::__construct($clazz, $instance, $options);
    }

    /**
     * @return void
     */
    protected function initElements()
    {
        parent::initElements();

        $element = $this->form->getElement(
            tao_helpers_Uri::encode(TestCenterByOrganisationId::ORGANISATION_ID_PROPERTY)
        );

        if ($element) {
            $element->addValidator(
                tao_helpers_form_FormFactory::getValidator('Callback', array(
                    'message' => __('Organisation Id Must be unique'),
                    'object' => $this->testCenterFormService,
                    'method' => 'validateOrganisationIdValue',
                    'param' => $this->instance ? $this->instance->getUri() : null
                ))
            );
        }
    }
}
