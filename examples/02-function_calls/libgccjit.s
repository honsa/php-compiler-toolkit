	.file	"fake.c"
	.text
	.p2align 4,,15
	.globl	add2
	.type	add2, @function
add2:
.LFB3:
	.cfi_startproc
.L4:
	leaq	(%rdi,%rsi,2), %rax
	ret
	.cfi_endproc
.LFE3:
	.size	add2, .-add2
	.ident	"GCC: (Ubuntu 6.3.0-12ubuntu2) 6.3.0 20170406"
	.section	.note.GNU-stack,"",@progbits
