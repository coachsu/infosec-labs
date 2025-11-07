FROM python:3.11
WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . /app
RUN chmod +x /app/wait-for-db.sh

ENV FLASK_APP=app.py
ENV PYTHONUNBUFFERED=1

CMD ["/app/wait-for-db.sh"]
