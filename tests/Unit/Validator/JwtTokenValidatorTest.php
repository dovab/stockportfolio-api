<?php

use App\Validator\JwtToken;
use App\Validator\JwtTokenValidator;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

beforeEach(function() {
    $this->tokenManager = $this->getMockBuilder(JWTManager::class)
        ->disableOriginalConstructor()
        ->getMock();

    $this->executionContextMock = $this->getMockBuilder(ExecutionContextInterface::class)
        ->disableOriginalConstructor()
        ->getMock();

    $this->constraintViolationBuilderMock = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)
        ->disableOriginalConstructor()
        ->getMock();
});

it('throws an exception when an invalid constraint is given', function () {
    $validator = new JwtTokenValidator($this->tokenManager);
    $validator->validate('', new NotNull());
})->expectException(UnexpectedTypeException::class);

it('should add an violation', function ($exceptionCode, $expectedMessage) {
    $this->tokenManager->expects($this->once())
        ->method('parse')
        ->willThrowException(new JWTDecodeFailureException($exceptionCode, ''));

    $this->executionContextMock
        ->expects($this->once())
        ->method('buildViolation')
        ->with($expectedMessage)
        ->willReturn($this->constraintViolationBuilderMock);

    $this->constraintViolationBuilderMock
        ->expects($this->once())
        ->method('addViolation')
        ->willReturn(null);

    $validator = new JwtTokenValidator($this->tokenManager);
    $validator->initialize($this->executionContextMock);
    $validator->validate('DUMMY', new JwtToken());
})->with([
    [JWTDecodeFailureException::EXPIRED_TOKEN, 'The token is expired.'],
    [JWTDecodeFailureException::UNVERIFIED_TOKEN, 'The token is unverified.'],
    [JWTDecodeFailureException::INVALID_TOKEN, 'The token is invalid.'],
]);

it('should check for correct actions', function($token, $constraintAction, $expectedMessage) {
    $this->tokenManager->expects($this->once())
        ->method('parse')
        ->willReturn($token);

    $this->executionContextMock
        ->expects($this->once())
        ->method('buildViolation')
        ->with($expectedMessage)
        ->willReturn($this->constraintViolationBuilderMock);

    $this->constraintViolationBuilderMock
        ->expects($this->once())
        ->method('addViolation')
        ->willReturn(null);

    $validator = new JwtTokenValidator($this->tokenManager);
    $validator->initialize($this->executionContextMock);
    $validator->validate('DUMMY', new JwtToken(null, null, null, $constraintAction));
})->with([
    [['sub' => 'test'], 'password_reset', 'This token does not have an action.'],
    [['sub' => 'test', 'action' => 'invalid'], 'password_reset', 'This token does not have the correct action.'],
]);

it('should not add a violation for correct tokens', function($action) {
    $this->tokenManager->expects($this->once())
        ->method('parse')
        ->willReturn(['sub' => 'test', 'action' => $action]);

    $this->executionContextMock
        ->expects($this->never())
        ->method('buildViolation');

    $this->constraintViolationBuilderMock
        ->expects($this->never())
        ->method('addViolation');

    $validator = new JwtTokenValidator($this->tokenManager);
    $validator->initialize($this->executionContextMock);
    $validator->validate('DUMMY', new JwtToken(null, null, null, $action));
})->with([
    ['test-action'],
]);
