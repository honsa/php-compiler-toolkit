function add1or2(long, long) : long

/tmp/libjit-dump.o:     file format elf64-x86-64


Disassembly of section .text:

00007f707d26613f <.text>:
    7f707d26613f:	55                   	push   %rbp
    7f707d266140:	48 8b ec             	mov    %rsp,%rbp
    7f707d266143:	48 83 ec 20          	sub    $0x20,%rsp
    7f707d266147:	4c 89 34 24          	mov    %r14,(%rsp)
    7f707d26614b:	4c 89 7c 24 08       	mov    %r15,0x8(%rsp)
    7f707d266150:	4c 8b ff             	mov    %rdi,%r15
    7f707d266153:	4c 8b f6             	mov    %rsi,%r14
    7f707d266156:	49 83 ff 00          	cmp    $0x0,%r15
    7f707d26615a:	0f 8f 05 00 00 00    	jg     0x7f707d266165
    7f707d266160:	e9 0f 00 00 00       	jmpq   0x7f707d266174
    7f707d266165:	49 8b c6             	mov    %r14,%rax
    7f707d266168:	48 89 45 f8          	mov    %rax,-0x8(%rbp)
    7f707d26616c:	48 ff c0             	inc    %rax
    7f707d26616f:	e9 0b 00 00 00       	jmpq   0x7f707d26617f
    7f707d266174:	49 8b c6             	mov    %r14,%rax
    7f707d266177:	48 89 45 f0          	mov    %rax,-0x10(%rbp)
    7f707d26617b:	48 83 c0 02          	add    $0x2,%rax
    7f707d26617f:	4c 8b 34 24          	mov    (%rsp),%r14
    7f707d266183:	4c 8b 7c 24 08       	mov    0x8(%rsp),%r15
    7f707d266188:	48 8b e5             	mov    %rbp,%rsp
    7f707d26618b:	5d                   	pop    %rbp
    7f707d26618c:	c3                   	retq   

end

