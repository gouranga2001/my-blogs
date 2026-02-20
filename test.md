# Change DNS Server in Ubuntu

Changing the DNS server on Ubuntu is an easy task, but needs to be done carefully. Here's how to do it in the command line.

Jul 14, 2024 — [Pratham Patel](https://learnubuntu.com/author/pratham/ "Pratham Patel")

## Change DNS Server in Ubuntu

Changing the DNS server on Ubuntu is an easy task, but needs to be done carefully.

This article shows you how to do it easily.

## Step 1: Check your current DNS Server

The first step is to always check, and note down the currently used DNS Servers – in case the change in DNS Server does not result in intended changes.

To show the current DNS servers that are being used per interface, use the 'resolvectl' command:

```bash
resolvectl status
```

Copy

For systems running **Ubuntu 20.04 or newer**, please use the following command:

```
systemd-resolve --status
```

Running either of these commands will show you the DNS server(s) being used by each network interface.

Let us see the output of the 'resolvectl' command on my computer:

```bash
$ resolvectl status
Global
       Protocols: -LLMNR -mDNS -DNSOverTLS DNSSEC=no/unsupported
resolv.conf mode: stub

Link 2 (enp1s0)
    Current Scopes: DNS
         Protocols: +DefaultRoute +LLMNR -mDNS -DNSOverTLS DNSSEC=no/unsupported
Current DNS Server: 8.8.8.8
       DNS Servers: 8.8.8.8 8.8.4.4
```

Copy

As evident from the output, my current DNS provider is Google.

I'd like to change that to something else, maybe Cloudflare.

## Step 2: Temporarily change DNS (to test it out)

Before you change the DNS settings permanently, it is best to change it temporarily and see the results.

If there is regression, no need to revert as this is only a temporary change. If the results are as expected, we will make it permanent.

To temporarily change the DNS server, edit the `/etc/resolv.conf` file.

In `/etc/resolv.conf`, the line which starts with the keyword `nameserver` deals with DNS Servers.

💡

NEVER remove the line that says `nameserver 127.0.0.53`. Comment it out by putting a pound/hash symbol at the beginning of that line.

Add one or two lines that begin with `nameserver` and [specify the IP address](https://learnubuntu.com/check-ip-address/) next to it. Below is what it should look like (if I want Cloudflare as my DNS provider):

```
nameserver 1.1.1.2
nameserver 1.0.0.2
```

The first line is your primary DNS server and second line is the fall-back DNS server. The fall-back DNS server is used when [for some reason] the primary DNS server is unavailable.

Once these two lines are added to your `/etc/resolv.conf` file, you should immediately see the results.

You can verify if the DNS server changed with the help of `dig` command.

```bash
$ dig google.com | grep SERVER
;; SERVER: 1.1.1.2#53(1.1.1.2) (UDP)
```

Copy

Grep-ing the output, we see that Cloudflare's DNS servers are being used. That confirms that the _temporary_ change in DNS server was in effect immediately.

After temporarily switching DNS, if you do not like the change, remove the lines that _you had added_ and uncomment the `nameserver 127.0.0.53` line.

### Available DNS Providers

In case you are not aware of IP addresses for DNS servers, below is the table of a few popular DNS providers, and their primary and fall-back addresses:

|Provider|Primary IP Address|Fall-back IP Address|
|---|---|---|
|Cloudflare|1.1.1.1|1.0.0.1|
|Cloudflare (malware blocking)|1.1.1.2|1.0.0.2|
|Google|8.8.8.8|8.8.4.4|
|Quad9|9.9.9.9|149.112.112.112|
|OpenDNS|208.67.222.222|208.67.220.220|

To use Cloudflare (with malware blocking) as my DNS provider, I will use the 1.1.1.2 and 1.0.0.2 IP addresses.

## Step 3: Permanently change DNS

After temporarily changing your DNS provider, if you are satisfied with the results, it is time to make this change permanent.

### Method 1: The easy way :)

To permanently change your DNS server, install the `resolvconf` package using the following command:

```bash
sudo apt-get install resolvconf
```

Copy

Once that is installed, edit the `/etc/resolvconf/resolv.conf.d/head` file and add the same `nameserver` lines to it like so (assuming [Cloudflare](https://www.cloudflare.com/en-gb/?ref=learnubuntu.com) as DNS provider):

```
nameserver 1.1.1.2
nameserver 1.0.0.2
```

Once that is done, start the `resolvconf.service` with the following command:

```bash
sudo systemctl enable --now resolvconf.service
```

Copy

That is all!

### Method 2: The not-so-easy way

One way to permanently change your DNS server is to edit the YAML file that resides in the `/etc/netplan/` directory.

Before that, note down the name of your network interface beforehand. You can do so using the `ip` command:

```bash
ip addr
```

Copy

That will list various network interfaces. Locate the interface and note it down.

Usually, there is only one file in `/etc/netplan/` directory, but the name is mostly different. If there are multiple files, grep all files for your interface name. That should narrow down the candidate file to one.

```bash
grep -H INTERFACE_NAME *.*
```

Copy

Once you know the filename, [open it for editing](https://learnubuntu.com/edit-files-command-line/). You should see something similar to my output:

```
network:
  ethernets:
    enp1s0:
      dhcp4: true
  version: 2
```

My network interface is called 'enp1s0', yours might be different.

Under my interface, I will add the `nameservers` field (below, not under `dhcp`), and another filed called `addresses` under it as well. I will specify the address in a bracket, separated by commas, like so:

```
network:
  ethernets:
    enp1s0:
      dhcp4: true
      nameservers:
        addresses: [1.1.1.2, 1.0.0.2]
  version: 2
```

Once that is done, save changes and exit.

Then, run the following command to make changes effective:

```bash
sudo netplan apply
```

Copy

Done! You have now permanently changed your DNS server :)

[

![Pratham Patel](https://www.gravatar.com/avatar/5ab808841681d9c8e3c93fbc7e135b66?s=250&r=x&d=mp)

](https://learnubuntu.com/author/pratham/)

[Pratham Patel](https://learnubuntu.com/author/pratham/)

Fell in love with Ubuntu the first time I tried it. Been distro-hopping since 2016.
