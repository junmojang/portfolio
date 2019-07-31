import java.util.*;
import java.io.*;

class encode
{
	static String set;
	
	public byte[] codeWord(byte[] in)
	{
		return in;
	}
	
	public String toString(int value)
	{
		return set.charAt(value) + "";
	}
	
	public String encode(byte[] in)
	{
		String stringOut = "";
		byte[] out = codeWord(in);
		for(int i = 0; i < out.length; i++)
			stringOut += toString((int)out[i]);
		return stringOut;
	}
}

class base64 extends encode
{
	public base64()
	{
		this.set = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	}
	
	@Override
	public byte[] codeWord(byte[] in)
	{
		int i;
		byte[] out = new byte[(in.length + 2) / 3 * 4];
		for(i = 0; i < in.length; i++)
		{
			if(i % 3 == 0)
				out[i/3*4] = (byte)((in[i] & 0xFC) >> 2);
			else if(i % 3 == 1)
				out[i/3*4 + 1] = (byte)(((in[i - 1] & 0x03) << 4) | ((in[i] & 0xF0) >> 4));
			else if(i % 3 == 2)
			{
				out[i/3*4 + 2] = (byte)(((in[i - 1] & 0x0F) << 2) | ((in[i] & 0xC0) >> 6));
				out[i/3*4 + 3] = (byte)(in[i] & 0x3F);
			}
		}
		if(in.length % 3 == 1)
		{
			out[i/3*4 + 0] = (byte)((in[i - 1] & 0xFC) >> 2);
			out[i/3*4 + 1] = (byte)((in[i - 1] & 0x03) << 4);
			out[i/3*4 + 2] = 64;
			out[i/3*4 + 3] = 64;
		}
		else if(in.length % 3 == 2)
		{
			out[i/3*4 + 0] = (byte)((in[i - 2] & 0xFC) >> 2);
			out[i/3*4 + 1] = (byte)(((in[i - 2] & 0x03) << 4) | ((in[i - 1] & 0xF0) >> 4));
			out[i/3*4 + 2] = (byte)((in[i - 1] & 0x0F) << 2);
			out[i/3*4 + 3] = 64;
		}
		return out;
	}
}

class ASCII extends encode
{
	@Override
	public byte[] codeWord(byte[] in)
	{
		return in;
	}
	
	@Override
	public String toString(int value)
	{
		if(32 <= value && value <= 126)
			return (char)value + "";
		else
			return " ";
	}
}

class Binary extends encode
{
	public Binary()
	{
		this.set = "01";
	}
	
	@Override
	public byte[] codeWord(byte[] in)
	{
		byte[] out = new byte[in.length * 8];
		for(int i = 0; i < in.length; i++)
			for(int j = 7; j >= 0; j--)
				out[i*8 + j] = (byte)((in[i] & (1 << (7 - j))) >> (7 - j));
		return out; 
	}
	
	@Override
	public String encode(byte[] in)
	{
		String stringOut = "";
		byte[] out = codeWord(in);
		for(int i = 0; i < out.length; i++) {
			stringOut += toString((int)out[i]);
			if(i % 8 == 7)
				stringOut += " ";
		}
		return stringOut;
	}
}

class Hex extends encode
{
	public Hex()
	{
		this.set = "0123456789ABCDEF";
	}
	
	@Override
	public byte[] codeWord(byte[] in)
	{
		byte[] out = new byte[in.length * 2];
		for(int i = 0; i <in.length; i++)
		{
			out[i*2] = (byte)(in[i] >> 4);
			out[i*2 + 1] = (byte)(in[i] & 0x0F);
		}
		return out;
	}
	
	@Override
	public String encode(byte[] in)
	{
		String stringOut = "";
		byte[] out = codeWord(in);
		stringOut += "0x";
		for(int i = 0; i < out.length; i++) {
			if(i % 2 == 0 && i != 0)
					stringOut += " 0x";
			stringOut += toString((int)out[i]);
		}
		return stringOut;
	}
}

class Decimal extends encode
{
	public Decimal()
	{
		this.set = "0123456789";
	}
	
	@Override
	public byte[] codeWord(byte[] in)
	{
		byte[] out = new byte[in.length * 3];
		for(int i = 0; i < in.length; i++)
		{
			out[i * 3] = (byte)(in[i] / 100);
			out[i * 3 + 1] = (byte)(in[i] / 10 % 10);
			out[i * 3 + 2] = (byte)(in[i] % 10);
		}
				
		return out; 
	}
	
	@Override
	public String encode(byte[] in)
	{
		String stringOut = "";
		byte[] out = codeWord(in);
		for(int i = 0; i < out.length; i++) {
			stringOut += toString((int)out[i]);
			if(i % 3 == 2)
				stringOut += " ";
		}
		return stringOut;
	}
}

public class hierarchy
{
	public static void main(String args[])
	{
		byte[] in = {65, 66, 67, 68, 69};
		base64 encodeObject = new base64();
		String out = encodeObject.encode(in);
		System.out.println(out);
		return;
	}
}

