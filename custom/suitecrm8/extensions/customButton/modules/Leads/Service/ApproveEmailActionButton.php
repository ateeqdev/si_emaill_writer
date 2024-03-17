<?php

namespace App\Extension\customButton\modules\Leads\Service;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use App\Engine\LegacyHandler\LegacyHandler;
use App\Process\Entity\Process;
use App\Process\Service\ProcessHandlerInterface;
use si_Email_Writer\Helpers\PrepareEmail;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class ApproveEmailActionButton extends LegacyHandler implements ProcessHandlerInterface, LoggerAwareInterface
{
    protected const MSG_OPTIONS_NOT_FOUND = 'Process options are not defined';
    protected const MSG_INVALID_TYPE = 'Invalid type';
    public const PROCESS_TYPE = 'record-approve-email';

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @inheritDoc
     */
    public function getHandlerKey(): string
    {
        return self::PROCESS_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function getProcessType(): string
    {
        return self::PROCESS_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function requiredAuthRole(): string
    {
        return 'ROLE_USER';
    }

    /**
     * @inheritDoc
     */
    public function getRequiredACLs(Process $process): array
    {
        $options = $process->getOptions();
        $module = $options['module'] ?? '';
        $id = $options['id'] ?? '';

        $editACLCheck =  [
            'action' => 'edit',
        ];

        if ($id !== '') {
            $editACLCheck['record'] = $id;
        }

        return [
            $module => [
                $editACLCheck
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function configure(Process $process): void
    {
        //This process is synchronous
        //We aren't going to store a record on db
        //thus we will use process type as the id
        $process->setId(self::PROCESS_TYPE);
        $process->setAsync(false);
    }

    /**
     * @inheritDoc
     */
    public function validate(Process $process): void
    {
        if (empty($process->getOptions())) {
            throw new InvalidArgumentException(self::MSG_OPTIONS_NOT_FOUND);
        }
    }

    /**
     * @inheritDoc
     */
    public function run(Process $process)
    {
        $options = $process->getOptions();
        $this->init();
        $this->startLegacyApp();
        $bean = \BeanFactory::getBean(ucfirst(ucfirst($options['module'])), $options['id'], array('disable_row_level_security' => true));
        $result=[];
        if (!$bean)
            $result['error'] = 'Record not found';
        if (!$bean->si_email_body)
            $result['error'] = 'Email body is empty';

        if ($bean->si_email_status == 'ready_for_approval')
            $bean->si_email_status = 'approved';
        else if ($bean->si_email_status == 'followup_written')
            $bean->si_email_status = 'followup_approved';
        else
        $result['error']= 'The Lead status does not require approval';

        $saved = $bean->save();
        if($saved){
            $result['statusCode'] = '200';
            $result['message'] = 'Email Approved';
        }
        
        $this->close();

        if (isset($result['error']) && !empty($result['error'])) {
            $process->setStatus('error');
            $process->setMessages([$result['error']]);
            return;
        }
        $responseData = [
            'handler' => 'redirect',
            'params' => [
                'route' => $options['module'] . '/record/' . $options['id'],
                'queryParams' => [$result]
            ],
            'reload' => true,
        ];
        $process->setStatus('success');
        $process->setMessages(['Email Approved']);
        $process->setData($responseData);
    }
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}