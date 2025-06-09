<?php
namespace Mail;

class Mail
{
	public function send(): void
	{
		$to = is_array($this->to) ? implode(', ', $this->to) : $this->to;

		$boundary = '----=_NextPart_' . md5(time());
		$altBoundary = '----=_NextPart_alt_' . md5(time());
		$encodedSender = '=?UTF-8?B?' . base64_encode($this->sender) . '?=';

		$headers = [
			'MIME-Version: 1.0',
			'Date: ' . date('D, d M Y H:i:s O'),
			'From: ' . $encodedSender . ' <' . $this->from . '>',
			'Reply-To: ' . ($this->reply_to
				? '=?UTF-8?B?' . base64_encode($this->reply_to) . '?= <' . $this->reply_to . '>'
				: $encodedSender . ' <' . $this->from . '>'),
			'Return-Path: ' . $this->from,
			'X-Mailer: PHP/' . phpversion(),
			'Content-Type: multipart/mixed; boundary="' . $boundary . '"',
			''
		];

		$message = [];

		if (!$this->html) {
			// Просто текстовое письмо
			$message[] = '--' . $boundary;
			$message[] = 'Content-Type: text/plain; charset="utf-8"';
			$message[] = 'Content-Transfer-Encoding: 8bit';
			$message[] = '';
			$message[] = $this->text;
		} else {
			// HTML письмо с альтернативной текстовой версией
			$message[] = '--' . $boundary;
			$message[] = 'Content-Type: multipart/alternative; boundary="' . $altBoundary . '"';
			$message[] = '';

			// Текстовая версия
			$message[] = '--' . $altBoundary;
			$message[] = 'Content-Type: text/plain; charset="utf-8"';
			$message[] = 'Content-Transfer-Encoding: 8bit';
			$message[] = '';
			$message[] = $this->text ?: 'To view this email, please use an HTML compatible email viewer.';
			$message[] = '';

			// HTML версия
			$message[] = '--' . $altBoundary;
			$message[] = 'Content-Type: text/html; charset="utf-8"';
			$message[] = 'Content-Transfer-Encoding: 8bit';
			$message[] = '';
			$message[] = $this->html;
			$message[] = '';

			// Конец альтернативной части
			$message[] = '--' . $altBoundary . '--';
			$message[] = '';
		}

		// Вложения
		foreach ($this->attachments as $attachment) {
			if (file_exists($attachment)) {
				$content = file_get_contents($attachment);
				$filename = basename($attachment);
				$encodedContent = chunk_split(base64_encode($content));

				$message[] = '--' . $boundary;
				$message[] = 'Content-Type: application/octet-stream; name="' . $filename . '"';
				$message[] = 'Content-Transfer-Encoding: base64';
				$message[] = 'Content-Disposition: attachment; filename="' . $filename . '"';
				$message[] = 'Content-ID: <' . urlencode($filename) . '>';
				$message[] = 'X-Attachment-Id: ' . urlencode($filename);
				$message[] = '';
				$message[] = $encodedContent;
				$message[] = '';
			}
		}

		// Конец письма
		$message[] = '--' . $boundary . '--';

		ini_set('sendmail_from', $this->from);

		$subject = '=?UTF-8?B?' . base64_encode($this->subject) . '?=';
		$fullMessage = implode(PHP_EOL, $message);
		$fullHeaders = implode(PHP_EOL, $headers);

		if ($this->parameter) {
			mail($to, $subject, $fullMessage, $fullHeaders, $this->parameter);
		} else {
			mail($to, $subject, $fullMessage, $fullHeaders);
		}
	}
}
