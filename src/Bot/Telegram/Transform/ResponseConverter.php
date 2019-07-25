<?php

namespace App\Bot\Telegram\Transform;

use App\Core\Interaction\InteractionResponse;
use SimpleTelegramBotClient\Builder\Action\SendMessageBuilder;
use SimpleTelegramBotClient\Builder\Keyboard\ArrayKeyboardButtonBuilder;
use SimpleTelegramBotClient\Builder\Keyboard\InlineKeyboardButtonBuilder;
use SimpleTelegramBotClient\Builder\Keyboard\ReplyKeyboardMarkupBuilder;
use SimpleTelegramBotClient\Dto\Action\SendMessage;

class ResponseConverter
{

    public function convertToTelegramMessage(InteractionResponse $interactionResponse): SendMessage
    {
        if ($interactionResponse->getText()) {
            $sendMessageBuilder = new SendMessageBuilder(
                $interactionResponse->getChatId(),
                $interactionResponse->getText()
            );

            $replyKeyboardMarkupBuilder = new ReplyKeyboardMarkupBuilder();
            if ($interactionResponse->getKeyboard()) {
                foreach ($interactionResponse->getKeyboard() as $row) {
                    $arrayKeyboardButtonBuilder = new ArrayKeyboardButtonBuilder();
                    foreach ($row as $cell) {
                        $arrayKeyboardButtonBuilder
                            ->add((new InlineKeyboardButtonBuilder($cell))->build())
                        ;
                    }
                    $replyKeyboardMarkupBuilder->addArrayOfKeyboardButton($arrayKeyboardButtonBuilder->build());
                }
            }
            $sendMessageBuilder->setReplyMarkup($replyKeyboardMarkupBuilder->build());
            return $sendMessageBuilder->build();
        }
        throw new \RuntimeException('wring');
    }
}
