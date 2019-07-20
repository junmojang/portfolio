RT_SECURE_NO_WARNINGS
#include<stdio.h>
enum funct { sll, srl = 0x02, jr = 0x08, add = 0x20, addu, sub, subu, and, or , nor = 0x27, slt = 0x2a, sltu };
enum opcode_I { beq = 0x4, bne, addi = 0x8, addiu, slti = 0xa, sltiu, andi, ori, lui = 0xf, lw = 0x23, lbu, lhu, sb = 0x28, sh, ll, sw = 0x2b, sc = 0x38 };
enum opcode_J { j = 0x2, jal };

int M[0x8000000] = { 0, };
int PC;
int R[32] = { 0 };
int rs, rt, rd, shamt, funct, immediate, address;
int temp;
int tmp;
unsigned int opcode;
//int functioncode;

void fetch()
{
        tmp = M[PC / 4];
}

void decode()
{
        opcode = (tmp & 0xfc000000) >> 26;
        //printf("opcode:%d\n", opcode);

        switch (opcode)
        {
        case 0: rs = (tmp & 0x03E00000) >> 21;
                rt = (tmp & 0x001F0000) >> 16;
                rd = (tmp & 0x0000F800) >> 11;
                shamt = (tmp & 0x000007C0) >> 6;
                funct = (tmp & 0x0000003F);
                break;
        case j: address = (tmp & 0x03FFFFFF);
                break;
        case jal: address = (tmp & 0x03FFFFFF);
                break;
        default: rs = (tmp & 0x003E00000) >> 21;
                rt = (tmp & 0x001F0000) >> 16;
                immediate = (tmp & 0x0000FFFF);
               break;
        }
}
//
void excution()
{
        int JumpAddr = PC & 0xF0000000 | address << 2;
        int SignExtImm = (short)immediate;
        int BranchAddr = SignExtImm << 2;
        int ZeroExtImm = immediate;
        switch (opcode)
        {
        case 0:
                switch (funct)
                {

                case sll: temp = R[rt] << shamt;
                        printf("Instuction : sll   ");
                        break;
                case srl: temp = R[rt] >> shamt;
                        printf("Instuction : srl   ");
                        break;
                case jr: PC = R[rs];
                        printf("Instuction : jr   ");
                        break;
                case add: temp = R[rs] + R[rt];
                        printf("Instuction : add   ");
                        break;
                case addu: temp = R[rs] + R[rt];
                        printf("Instuction : addu   ");
                        break;
                case sub:  temp = R[rs] - R[rt];
                        printf("Instuction : sub   ");
                        break;
                case subu:  temp = R[rs] - R[rt];
                        printf("Instuction : subu   ");
                        break;
                case and:  temp = R[rs] & R[rt];
                       printf("Instuction : and   ");
                        break;
                case or :  temp = R[rs] | R[rt];
                        printf("Instuction : or   ");
                        break;
                case nor:  temp = !(R[rs] | R[rt]);
                        printf("Instuction : nor   ");
                        break;
                case slt:  temp = (R[rs]<R[rt]) ? 1 : 0;
                        printf("Instuction : slt   ");
                        break;
                case sltu: temp = (R[rs]<R[rt]) ? 1 : 0;
                        printf("Instuction : sltu   ");
                        break;
                default:
                        break;

                }
                break;
        case j: PC = JumpAddr;
                printf("Instuction : j   ");
                break;
        case jal: R[31] = PC + 8; PC = JumpAddr;
                printf("Instuction : jal   ");

                break;
        case beq: if (R[rs] == R[rt])
                PC = PC + 4 + BranchAddr;
                          else PC = PC + 4;
                          printf("Instuction : beq   ");
                          break;
        case bne:  if (R[rs] != R[rt])
                PC = PC + 4 + BranchAddr;
                           else PC = PC + 4;
                           printf("Instuction : bne   ");
                           break;
        case addi: temp = R[rs] + SignExtImm;
                printf("Instuction : addi   ");
                break;
        case addiu: temp = R[rs] + SignExtImm;
                printf("Instuction : addiu   ");
                break;
        case slti: temp = (R[rs]<SignExtImm) ? 1 : 0;
                printf("Instuction : slti   ");
                break;
        case sltiu: temp = (R[rs]<SignExtImm) ? 1 : 0;
                printf("Instuction : sltiu   ");
                break;
        case andi: temp = R[rs] & ZeroExtImm;
                printf("Instuction : andi   ");
                break;
        case ori: temp = R[rs] | ZeroExtImm;
                printf("Instuction : ori   ");
                break;
        case lui: temp = immediate << 16;
                printf("Instuction : lui   ");
                break;
        default:
                break;
        }
}
void writeback()
{
        switch (opcode)
        {
        case 0: if (rd != 0) R[rd] = temp;
                break;
        case j:break;
        case jal:
                break;
        default: if (rt != 0) R[rt] = temp;
                break;
        }
}

void Maccess()
{
        int SignExtImm = (short)immediate;
        switch (opcode)
        {
        case lw: temp = M[(R[rs] + SignExtImm) / 4];
                printf("Instuction : lw   ");
                break;
        case lbu: temp = M[R[rs] + SignExtImm] & 0xFF;
                printf("Instuction : lbu   ");
                break;
        case lhu: temp = M[R[rs] + SignExtImm] & 0xFFFF;
                printf("Instuction : temp   ");
                break;
        case sb: M[R[rs] + SignExtImm] = R[rt] & 0xFF;
                printf("Instuction : sb   ");
                break;
        case sh: M[R[rs] + SignExtImm] = R[rt] & 0xFFFF;
                printf("Instuction : sh   ");
                break;
        case ll: temp = M[R[rs] + SignExtImm];
                printf("Instuction : ll   ");
                break;
        case sw: M[(R[rs] + SignExtImm) / 4] = R[rt];
                printf("Instuction : sw   ");
                break;
        }
}



int main(void) {

        int i = 0;
        FILE*POINT = fopen("simple.bin", "rb");
        int cycle = 0;
        PC = 0;


        for (i = 0; !feof(POINT); i++)
        {
                fread(M + i, 4, 1, POINT);
                M[i] = (*(M + i) & 0xFF000000) >> 24 | (*(M + i) & 0xFF0000) >> 8 | (*(M + i) & 0xff00) << 8 | (*(M + i) & 0xFF) << 24;
                printf("%x\n", M[i]);
        }

        R[31] = -1;
        R[29] = 0x8000000;
        for (; PC != -1;)
        {
                fetch();
                decode();
                excution();
                Maccess();
                if (opcode != sw)
                        writeback();
                printf("%x\n", M[PC / 4]);
                printf("PC:%d\n", PC);
                cycle++;
                printf("current cycle:%d\n", cycle);
                switch (opcode)
                {
                case 0: printf("Rister:%d, rs:%d, opcode:%d \n", rd, rs, opcode);
                        break;
                default: printf("Rister:%d, rs:%d, opcode:%d \n", rt, rs, opcode);
                        break;
                }
                if (funct != jr&&opcode != j&&opcode != bne&&opcode != beq&&opcode != jal)
    PC = PC + 4;
                funct = 0;
                opcode = 0;
                printf("current v0:%d\n", R[2]);

                printf("--------------------------------------\n");
        }
        printf("total cycle:%d\n", cycle);


        printf("Final v0:%d\n", R[2]);

        return 0;

}


