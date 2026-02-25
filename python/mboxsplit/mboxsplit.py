import mailbox

input_mbox = '~/Downloads/pstevens.mbox'
output_prefix = 'mailbox_part_'
max_size = 250 * 1024 * 1024  # 250 MB

mbox = mailbox.mbox(input_mbox)
part = 1
current_mbox = mailbox.mbox(f'{output_prefix}{part}.mbox')
current_size = 0

for message in mbox:
    message_bytes = message.as_bytes()
    message_size = len(message_bytes)
    if current_size + message_size > max_size:
        current_mbox.close()
        part += 1
        current_mbox = mailbox.mbox(f'{output_prefix}{part}.mbox')
        current_size = 0
    current_mbox.add(message)
    current_size += message_size

current_mbox.close()
