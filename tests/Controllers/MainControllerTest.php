<?php

namespace Tests\Controllers;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\MainController;
use Vluzrmos\SlackApi\Contracts\SlackTeam;
use Vluzrmos\SlackApi\Contracts\SlackChannel;
use Vluzrmos\SlackApi\Contracts\SlackUserAdmin;

class MainControllerTest extends TestCase
{
    /* @var Request | \PHPUnit_Framework_MockObject_MockObject */
    private $request;

    /* @var SlackTeam | \PHPUnit_Framework_MockObject_MockObject */
    private $slackUserAdmin;

    /* @var SlackTeam | \PHPUnit_Framework_MockObject_MockObject */
    private $slackTeam;

    /* @var SlackChannel | \PHPUnit_Framework_MockObject_MockObject */
    private $slackChannel;

    /* @var MainController */
    private $mainController;

    public function setUp()
    {
        parent::setUp();

        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->slackUserAdmin = $this->getMockBuilder(SlackUserAdmin::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->slackTeam = $this->getMockBuilder(SlackTeam::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->slackChannel = $this->getMockBuilder(SlackChannel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mainController = new MainController();
    }

    public function testIndexWorksCorrectly()
    {
        $this->slackTeam->expects($this->once())
            ->method('info')
            ->willReturn((object) [
                'ok' => true,
                'team' => (object) [
                    'name' => 'Test Team'
                ]
            ]);

        $this->slackChannel->expects($this->once())
            ->method('all')
            ->willReturn((object) [
                'channels' => [
                    (object) [
                        'name' => 'general',
                        'members' => ['user', 'user', 'user']
                    ]
                ]
            ]);

        $expectedView = view('index', [
            'teamName' => 'Test Team',
            'usersCount' => 3
        ]);

        $resultView = $this->mainController->getIndex($this->slackTeam, $this->slackChannel);

        $this->assertEquals($expectedView, $resultView);
    }

    public function testIndexThrowsErrorOnAuthError()
    {
        $this->slackTeam->expects($this->once())
            ->method('info')
            ->willReturn((object)[
                'ok' => false
            ]);

        $expectedMessage = trans('lines.auth_error');

        $resultMessage = $this->mainController->getIndex($this->slackTeam, $this->slackChannel);

        $this->assertEquals($expectedMessage, $resultMessage);
    }

    public function testInviteWorksCorrectly()
    {
        $this->slackUserAdmin->expects($this->once())
            ->method('invite')
            ->willReturn((object) [
                'ok' => true
            ]);

        $expectedView = view('success');

        $resultView = $this->mainController->postIndex($this->request, $this->slackUserAdmin);

        $this->assertEquals($expectedView, $resultView);
    }

    public function testInviteReturnsSpecificError()
    {
        $this->slackUserAdmin->expects($this->once())
            ->method('invite')
            ->willReturn((object) [
                'ok' => false,
                'error' => 'already_invited'
            ]);

        $expectedView = view('error', [
            'message' => trans('lines.errors.already_invited')
        ]);

        $resultView = $this->mainController->postIndex($this->request, $this->slackUserAdmin);

        $this->assertEquals($expectedView, $resultView);
    }

    public function testInviteReturnsGenericError()
    {
        $this->slackUserAdmin->expects($this->once())
            ->method('invite')
            ->willReturn((object) [
                'ok' => false,
                'error' => 'unknown_error'
            ]);

        $expectedView = view('error', [
            'message' => trans('lines.errors.generic')
        ]);

        $resultView = $this->mainController->postIndex($this->request, $this->slackUserAdmin);

        $this->assertEquals($expectedView, $resultView);
    }
}
